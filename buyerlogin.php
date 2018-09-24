<?php
	session_start();

	# Unset session to be able to store new info for the new user
	if(isset($_SESSION["buyerloggedin"]) && $_SESSION["buyerloggedin"] == 1){
		$_SESSION = array();
	}
?>
<!--
Login as Buyer
-->
<!DOCTYPE html>
<html>
	<head>
		<title>Buyer Login</title>
		<h1>Log in as Buyer</h1>
	</head>

	<body>
		<form action = "buyer.php" method = "POST">
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