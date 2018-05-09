<?php
//Start the session
session_start();

include ("functions.php"); //contains database functions
$db = new Functions("it635"); //instantiate functions, connect to db

//check login permissions (users and managers allowed)
if($_SESSION["login"] != 1 && $_SESSION["login"] != 2) {
	die ("Invalid credentials, please try again");
}

$arg1 = $_GET["arg1"]; //specifies type of query
$arg2 = $_GET["arg2"]; //additional arguments for query
$arg3 = $_GET["arg3"];
$arg4 = $_GET["arg4"];
$arg5 = $_GET["arg5"];
$arg6 = $_GET["arg6"];
$arg7 = $_GET["arg7"];
$arg8 = $_GET["arg8"];

switch ($arg1) {
	case "va": //view assets
		echo("<h3>View Assets</h3>");
		$response = $db->viewAssets($arg2);
		if($_SESSION["login"] == 2) { //display filters for managers
			echo("Filter: <select name='filter' id='filter'><option value='undefined'>All</option>");
			echo("<option value='distributed'>Distributed</option>");
			echo("<option value='stock'>In Stock</option></select>");
			echo("<button type='button' onClick='query(\"va\", document.getElementById(\"filter\").value)'>GO</button>");
		}
		echo("<table><tr><th>ID</th><th>Name</th><th>Owner</th><th>Current User</th><th>Condition</th><th>Notes</th></tr>");
		foreach ($response as $row) {
			echo '<tr>';
			echo '<td>' . $row['aid'] . '</td>';
			echo '<td>' . $row['name'] . '</td>';
			echo '<td>' . $row['owner'] . '</td>';
			echo '<td>' . $row['curr'] . '</td>';
			echo '<td>' . $row['assetCondition'] . '</td>';
			echo '<td>' . $row['notes'] . '</td>';
			echo '</tr>';
		}
		echo ("</table>");
		break;
	case "aa": //add asset
		$response = $db->addAsset($arg2, $arg3, $arg4, $arg5, $arg6);
		echo($response);
		break;
	case "ra": //remove asset
		$response = $db->removeAsset($arg2);
		echo($response);
		break;
	case "vr": //view requests
		echo("<h3>View Requests</h3>");
		$response = $db->viewRequests($arg2, $arg3);
		if($_SESSION["login"] == 2) { //display filters for managers
			echo("Filter: <select name='filter2' id='filter2'><option value='undefined'>All</option>");
			echo("<option value='open'>Open</option>");
			echo("<option value='closed'>Closed</option></select>");
			echo("<button type='button' onClick='query(\"vr\", document.getElementById(\"filter2\").value)'>GO</button>");
		}
		echo("<table><tr><th>Request ID</th><th>Requester ID</th><th>Asset ID</th><th>Type</th><th>Status</th><th>Opened</th><th>Closed</th><th>Details</th>");
		foreach ($response as $row) {
			echo '<tr>';
			echo '<td>' . $row['rid'] . '</td>';
			echo '<td>' . $row['eid'] . '</td>';
			echo '<td>' . $row['aid'] . '</td>';
			echo '<td>' . $row['reqtype'] . '</td>';
			echo '<td>' . $row['status'] . '</td>';
			echo '<td>' . $row['opened'] . '</td>';
			echo '<td>' . $row['closed'] . '</td>';
			echo '<td>' . $row['details'] . '</td>';
			echo '</tr>';
		}
		echo ("</table>");
		break;
	case "ar": //add request
		$response = $db->addRequest($arg2, $arg3, $arg4, $arg5);
		echo($response);
		break;
	case "mr": //manage request
		$response = $db->manageRequest($arg2, $arg3);
		echo($response);
		break;
	case "vd": //view descriptions
		$response = $db->viewDescriptions();
		echo "<pre>";
		print_r($response);
		echo "</pre>";
		/*
		foreach($response[0] as $child) {
   			echo (key($child) . " - " . $child . "<br>");
		}
		*/
		break;
	case "ad": //add description
		$response = $db->addDescription($arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8);
		echo($response);
		break;
	case "lo": //log out
		$response = $db->logout();
		break;
}
?>

