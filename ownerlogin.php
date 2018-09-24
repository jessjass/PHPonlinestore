<?php
	session_start();
	
	# Unset session to be able to store new info for the new use
	if(isset($_SESSION["ownerloggedin"]) && $_SESSION["ownerloggedin"] == 1){
		$_SESSION = array();
	}
?>
<!--
Login as Owner
-->
<!DOCTYPE html>
<html>
	<head>
		<title>Owner Login</title>
		<h1>Log in as Owner</h1>
	</head>

	<body>
		<form action = "owner.php" method = "POST">
			<b>Email: </b>
			<input type = "text" name = "email" size = "30" maxlength = "30"></input>
			<br></br>
			<b>Password: </b>
			<input type = "password" name = "password" size = "30" maxlegnth = "30"></input>
			<br></br>
			<input type = "submit" value = "Login">
		</form>
	</body>
</html>