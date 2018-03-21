<?php
//Start the session
session_start();

include ("functions.php"); //contains database functions
$db = new Functions("it635"); //instantiate functions, connect to db

//attempt login with form data and store permissions in session data
$_SESSION["login"] = $db->login($_POST["eid"],$_POST["pass"]);

//check login permissions (users and managers allowed)
if($_SESSION["login"] != 1 && $_SESSION["login"] != 2) {
	die ("Invalid credentials, please <a href='./index.html'>try again</a>.");
}
//save employee id as session data
$_SESSION["eid"] = $_POST["eid"];

?>

<html>
<head>
<style>
	button {width: 250px;}
</style>
<script type="text/javascript">
	//ajax for query.php; takes arguments to determine desired query/function
	function query(arg1, arg2, arg3, arg4, arg5, arg6) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("content").innerHTML = this.responseText;
			}
		};
		xhttp.open("GET", "./query.php?arg1=" + arg1 + "&arg2=" + arg2 + "&arg3=" + arg3 + "&arg4=" + arg4 + "&arg5=" + arg5 + "&arg6=" + arg6, true);
		xhttp.send();
		if (arg1 == "lo") {
			window.location.href = './index.html'; //go to login page
		}
	}

	//generate form for managing equipment
	function manageForm() {
        document.getElementById("content").innerHTML =
		"<h3>Manage Equipment</h3>" +
		"<button type='button' onClick='addAssetForm()'>Add Asset</button>" +
        "<button type='button' onClick='removeAssetForm()'>Remove Asset</button>";
	}
	//generate form for adding an asset
	function addAssetForm() {
        document.getElementById("content").innerHTML =
        "<h3>Manage Equipment</h3>" +
        "<button type='button' onClick='addAssetForm()'>Add Asset</button>" +
        "<button type='button' onClick='removeAssetForm()'>Remove Asset</button><br><br>" +
		"<fieldset>" +
		"<legend>Add an Asset</legend>" +
		//aid is autogenerated
		"<label for='name'>Name</label>" +
        	"<input name='name' id='name' type=text required><br>" +
		"<label for='owner'>Owner</label>" +
        	"<input name='owner' id='owner' type=text required><br>" +
		"<label for='curr'>Current User</label>" +
        	"<input name='curr' id='curr' type=text required><br>" +
		"<label for='assetCondition'>Condition</label>" +
        	"<select name='assetCondition' id='assetCondition' required>" +
		"<option value='Broken'>Broken</option><option value='Poor'>Poor</option>" +
		"<option value='Okay'>Okay</option><option value='Good'>Good</option>" +
		"<option value='New'>New</option></select><br>" +
		"<label for='notes'>Notes</label>" +
        	"<input name='notes' id='notes' type=text><br>" +
		"<button type='button' onClick='query(\"aa\", document.getElementById(\"name\").value, document.getElementById(\"owner\").value, document.getElementById(\"curr\").value, document.getElementById(\"assetCondition\").value, document.getElementById(\"notes\").value)'>Submit</button>" +
		"</fieldset>";
	}
	//generate form for removing an asset
	function removeAssetForm() {
        document.getElementById("content").innerHTML =
        "<h3>Manage Equipment</h3>" +
        "<button type='button' onClick='addAssetForm()'>Add Asset</button>" +
        "<button type='button' onClick='removeAssetForm()'>Remove Asset</button><br><br>" +
		"<fieldset>" +
		"<legend>Remove an Asset</legend>" +
		"<label for='aid'>Asset ID</label>" +
        "<input name='aid' id='aid' type=text required><br>" +
		"<button type='button' onClick='query(\"ra\", document.getElementById(\"aid\").value)'>Submit</button>";
		"</fieldset>";
	}

	//generate form for submitting request
	function requestForm() {
		document.getElementById("content").innerHTML =
		"<h3>Submit a Request</h3>" +
		"<fieldset>" +
		//rid is autogenerated
		//eid is currently logged in user
		"<label for='aid2'>Asset ID</label>" +
		"<input name='aid2' id='aid2' type=text><br>" +
		"<label for='type'>Request Type</label>" +
		"<select name='type' id='type'>" +
		"<option value='Use'>Use</option>" +
		"<option value='Purchase'>Purchase</option>" +
		"<option value='Retirement'>Retirement</option>" +
		"</select><br>" +
		//status is Open
		//opened is current time, closed is null
		"<label for='details'>Describe the details of your request:</label>" +
		"<input name='details' id='details' type=text><br>" +
		"<button type='button' onClick='query(\"ar\", \"" + eid +
		"\", document.getElementById(\"aid2\").value, document.getElementById(\"type\").value, document.getElementById(\"details\").value)'>Submit</button>" +
		"</fieldset>";
	}
	//generate form for approving/denying requests
	function manageRequestForm() {
		document.getElementById("content").innerHTML =
		"<h3>Manage Requests</h3>" +
		"<fieldset>" +
		"<label for='rid'>Request ID</label>" +
		"<input name='rid' id='rid' type=text><br>" +
		"<label for='decision'>Decision</label>" +
		"<select name='decision' id='decision'>" +
		"<option value='Approved'>Approve</option>" +
		"<option value='Denied'>Deny</option>" +
		"</select><br>" +
		"<button type='button' onClick='query(\"mr\", document.getElementById(\"rid\").value, document.getElementById(\"decision\").value)'>Submit</button>" +
		"</fieldset>";
	}

</script>
</head>

<div id="header">
<p>Loading...</p>
</div>

<div id="content">
<h2>Welcome to the Enterprise Asset Management System</h2>
<h3>Please select a function above</h3>
</div>

<script>
	//generate page based on role
	<?php
		echo("var role = ".$_SESSION["login"]."; "); //get role from php
	 	echo("var eid = \"".$_SESSION["eid"]."\";"); //get eid from php
	?>

	if (role == 1) { //user role
		document.getElementById("header").innerHTML =
		"<button type='button' onClick='query(\"va\", \"stock\")'>View Available Assets</button>" +
		"<button type='button' onClick='requestForm()'>Submit a Request</button>" +
		"<button type='button' onClick='query(\"vr\", \"personal\", \"" + eid + "\")'>View Your Requests</button>" +
		"<button type='button' onClick='query(\"lo\")'>Log Out</button>" +
		"<br><hr>";
		document.getElementById("content").innerHTML =
		"<h2>Welcome to the Enterprise Asset Management System</h2>" +
		"<h3>Please select a function above</h3>" +
		"<h3>User Level: Employee</h3>";
	} else if (role == 2) { //manager role
		document.getElementById("header").innerHTML =
		"<button type='button' onClick='query(\"va\")'>View Assets</button>" +
		"<button type='button' onClick='query(\"vr\")'>View Requests</button>" +
		"<button type='button' onClick='manageRequestForm()'>Manage Requests</button>" +
		"<button type='button' onClick='manageForm()'>Manage Equipment</button>" +
		"<button type='button' onClick='query(\"lo\")'>Log Out</button>" +
		"<br><hr>";
		document.getElementById("content").innerHTML =
		"<h2>Welcome to the Enterprise Asset Management System</h2>" +
		"<h3>Please select a function above</h3>" +
		"<h3>User Level: Manager</h3>";
	} else {
		document.getElementById("content").innerHTML = "<p>Session expired, please <a href='./index.html'>log in</a> again."
	}
</script>
</html>