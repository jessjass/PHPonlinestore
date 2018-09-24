<?php
	session_start();

	# Check to see if buyer logged in from buyer.php (not an owner typing url)
	if(!isset($_SESSION["buyerloggedin"]) || $_SESSION["buyerloggedin"] == 0){
		header("Location: http://localhost:8082/project2/login.html");
	}

	# Update category to selected category
	$_SESSION["category"] = $_POST["category"];
	header("Location: http://localhost:8082/project2/buyer.php");

?>