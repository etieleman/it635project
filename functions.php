<?php class Functions {
	private $db;
	//connects to specified database, echoes error message if error
	public function __construct($database) {
		$this->db = new mysqli("localhost","root","it635root",$database);
		if ($this->db->connect_errno != 0) {
			echo "Error connecting to database: ".$this->db->connect_error.PHP_EOL;
			exit();
		}
	}
	//closes database connection
	public function __destruct() {
		if (isset($this->db)) {
			$this->db->close();
		}
	}
	//attempt to authenticate user against database, get role if successful
	//0 = unsuccessful login/no permissions
	//1 = user, limited permissions
	//2 = manager/admin, full permissions
	public function login($username,$password) {
		$un = $this->db->real_escape_string($username);
		$pw = $this->db->real_escape_string($password);
		$query = "SELECT * FROM employees WHERE eid = '$un';";
		$result = $this->db->query($query);
		while ($row = $result->fetch_assoc()) {
			if ($row["pass"] == hash("sha256", $pw)) {
				if ($row["role"] == "User") {
					return 1; //successful user login
				} else if ($row["role"] == "Manager") {
					return 2; //successful manager login
				}
			}
		}
		return 0; //unsuccessful login or no assigned role
	}
	//list equipment in database
	public function viewAssets($modifier) {
		$mod = $this->db->real_escape_string($modifier);
		if ($mod == "undefined") { //show all equipment
			if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
			$query = "SELECT * FROM assets WHERE curr <> 'retired';";
		} else if ($mod == "distributed") { //show only distributed equipment
			if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
			$query = "SELECT * FROM assets WHERE curr <> 'storage' AND curr <> 'retired';";
		} else if ($mod == "stock") { //show only equipment in storage
			$query = "SELECT * FROM assets WHERE curr = 'storage';";
		} else {
			die("Invalid command");
		}
		if (!$queryResponse = $this->db->query($query)) { return "Error viewing assets!"; }
		$response = array();
		while($row = $queryResponse->fetch_assoc()) {
			$response[] = $row;
		}
		return $response;
	}
	//view asset descriptions
	public function viewDescriptions() {
		//connect to MongoDB
		require_once '/var/www/html/it635/vendor/autoload.php';
		$mongo = (new MongoDB\Client("mongodb://root:it635password@ds151153.mlab.com:51153/it635"))->it635->descriptions;
		//return array with descriptions
		$cursor = $mongo->find();
		$response = iterator_to_array($cursor);
		return $response;
	}
	//add description to mongo
	public function addDescription($name, $i1, $d1, $i2, $d2, $i3, $d3) {
		//connect to MongoDB
                require_once '/var/www/html/it635/vendor/autoload.php';
                $mongo = (new MongoDB\Client("mongodb://root:it635password@ds151153.mlab.com:51153/it635"))->it635->descriptions;
		//add specified document to database
		$doc = array(
			"name" => "$name",
			"$i1"  => "$d1",
			"$i2"  => "$d2",
			"$i3"  => "$d3"
		);
		$mongo->insertOne($doc);
		return "Description successfully added!";
	}
	//add asset to database
	public function addAsset($assetName, $assetOwner, $currentOwner, $condition, $note) {
		if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
		$name = $this->db->real_escape_string($assetName);
		$owner = $this->db->real_escape_string($assetOwner);
		$curr = $this->db->real_escape_string($currentOwner);
		$assetCondition = $this->db->real_escape_string($condition);
		$notes = $this->db->real_escape_string($note);
		$query = "CALL addAsset('$name', '$owner', '$curr', '$assetCondition', '$notes');"; //call to stored procedure
		if(!$queryResponse = $this->db->query($query)) {
			return "Error adding asset to database!";
        } else {
			return "Successfully added asset!";
        }
	}
	//remove asset from database
	public function removeAsset($asset) {
		if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
		$aid = $this->db->real_escape_string($asset);
		$query = "UPDATE assets SET curr = 'retired' WHERE aid = $aid;";
		if(!$queryResponse = $this->db->query($query)) {
			return "Error retiring asset!";
		} else if($this->db->affected_rows == 0) {
			return "Asset not found!";
		} else {
			return "Asset successfully retired!";
		}
	}
	//list requests in database
	public function viewRequests($modifier, $userID) {
		$mod = $this->db->real_escape_string($modifier);
		$eid = $this->db->real_escape_string($userID);
		if ($mod == "undefined") { //show all requests
			if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
			$query = "SELECT * FROM requests;";
		} else if ($mod == "open") { //show only open requests
			if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
			$query = "SELECT * FROM requests WHERE status = 'Open';";
		} else if ($mod == "closed") { //show only closed requests
			if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
			$query = "SELECT * FROM requests WHERE status = 'Approved' OR status = 'Denied';";
		} else if ($mod == "personal") { //show only a user's personal requests
			$query = "SELECT * FROM requests WHERE eid = '$eid';";
		} else {
			die("Invalid command");
		}
		if(!$queryResponse = $this->db->query($query)) { return "Error viewing requests!"; }
		$response = array();
		while($row = $queryResponse->fetch_assoc()) {
			$response[] = $row;
		}
		return $response;
	}
	//add request to database
	public function addRequest($requesterID, $assetID, $type, $reqDetails) {
		$eid = $this->db->real_escape_string($requesterID);
		$aid = $this->db->real_escape_string($assetID);
		$reqtype = $this->db->real_escape_string($type);
		$details = $this->db->real_escape_string($reqDetails);
		if ($reqtype == "Use" || $reqtype == "Retirement") { //these require an AID
			$query = "INSERT INTO requests (eid, aid, reqtype, status, opened, closed, details) VALUES ('$eid', $aid, '$reqtype', 'Open', NOW(), NULL, '$details');";
		} else if ($reqtype == "Purchase") { //these don't need an AID
			$query = "INSERT INTO requests (eid, aid, reqtype, status, opened, closed, details) VALUES ('$eid', NULL, '$reqtype', 'Open', NOW(), NULL, '$details');";
		}
		if(!$queryResponse = $this->db->query($query)) {
			return "Error adding request to database!";
        } else {
			return "Successfully added request!";
        }
	}
	//approve or deny a request
	public function manageRequest($requestID, $decision) {
		if ($_SESSION["login"] != 2) { die("Insufficient permissions for operation!"); }
		$rid = $this->db->real_escape_string($requestID);
		$status = $this->db->real_escape_string($decision);
		if ($status == "Approved") { //request approved, make changes if applicable
			$query = "UPDATE requests SET status = 'Approved', closed = NOW() WHERE rid = $rid; "; //base query
			//obtain information about request for further operations
			$infoQuery = "SELECT eid, aid, reqtype FROM requests WHERE rid = $rid;";
			if (!$info = $this->db->query($infoQuery)) { return "Error updating request!"; }
			else if ($info->num_rows == 0) { return "Request not found!"; }
			$row = $info->fetch_assoc();
			$type = ($row['reqtype']);
			if ($type == "Use") { //perform asset transfer
				$eid = ($row['eid']); //employee to transfer to
				$aid = ($row['aid']); //asset to transfer
				$query .= "UPDATE assets SET curr = '$eid' WHERE aid = $aid;";
			} else if ($type == "Retirement") { //retire asset
				$aid = ($row['aid']); //asset to remove
				$query .= "UPDATE assets SET curr = 'retired' WHERE aid = $aid;";
			} //otherwise no change to assets
		} else if ($status == "Denied") { //request denied, no changes to asset
			$query = "UPDATE requests SET status = 'Denied', closed = NOW() WHERE rid = $rid;";
		}
		if (!$queryResponse = $this->db->multi_query($query)) {
			return "Error updating request!";
		} else if ($this->db->affected_rows == 0) {
			return "Request not found!";
        	} else {
			return "Successfully updated request!";
        	}
	}
	//delete session variables, destroy session
	public function logout() {
		session_start();
		$_SESSION = array();
		session_destroy();
		return 1;
	}
}
?>
