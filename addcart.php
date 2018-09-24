<?php
	session_start();

	# Check to see if buyer logged in from buyer.php (not an owner typing url)
	if(!isset($_SESSION["buyerloggedin"]) || $_SESSION["buyerloggedin"] == 0){
		header("Location: http://localhost:8082/project2/login.html");
	}

	# Connet to DB
	$db = new mysqli('localhost', 'root', 'secret', 'store');
	      if ($db->connect_error):
	         die ("Could not connect to db: " . $db->connect_error);
	      endif;

	# Add item to buyer's cart
	$buyer = $_SESSION["buyerid"];
	$itemid = $_POST["add"];
	$query = "insert into Cart values('$buyer', '$itemid')";

	# Reduce the number of items in the store
	$result = $db->query($query);
	$query = "update Items set quantity=quantity-1 where Items.Item_id = '$itemid'";
	$result2 = $db->query($query);

	# Return to store
	header("Location: http://localhost:8082/project2/buyer.php");
	
?>