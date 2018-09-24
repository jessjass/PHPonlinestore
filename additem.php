<?php
	session_start();

	# Check to see if owner logged in from owner.php (not a user typing url)
	if(!isset($_SESSION["ownerloggedin"]) || $_SESSION["ownerloggedin"] == 0){
		header("Location: http://localhost:8082/project2/login.html");
	}

	# Connet to DB
	$db = new mysqli('localhost', 'root', 'secret', 'store');
	      if ($db->connect_error):
	         die ("Could not connect to db: " . $db->connect_error);
	      endif;

	# Get variables from POST
	$name = $_POST["itemname"];
	$cat = $_POST["category"];
	$description = $_POST["description"];
	$cost = $_POST["cost"];
	$quantity = $_POST["quantity"];
	$owner = $_SESSION["owner"];

	# Insert the specified item into the Items table
	$query = "insert into Items values('$name', '$cat', '$description', '$cost', '$quantity', '$owner', NULL)";
	$result = $db->query($query) or die ("Invalid insert " . $db->error);

	if($result){
		header("Location: http://localhost:8082/project2/owner.php");
	}else{
		echo "<h1 align=\"center\">Insertion Faild</h1>";
		echo "<h3 align=\"center\"><a href=\"http://localhost:8082/project2/owner.php\">Owner Page</a></h3>";
	}
?>