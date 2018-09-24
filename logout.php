<?php
	session_start();

	# If buyer is logging out, remove items from thier cart and add them back to store
	if($_SESSION["buyerloggedin"] == 1){

		# Connect to DB
		$db = new mysqli('localhost', 'root', 'secret', 'store');
		      if ($db->connect_error):
		         die ("Could not connect to db: " . $db->connect_error);
		      endif;

		# Get items from buyer's cart
		$buyer = $_SESSION["buyerid"];
		$items = $db->query("select Cart.Item_id from Cart where Cart.Buyer_id = '$buyer'");
		$rows = $items->num_rows;

		# Add items back to store
		for ($i  = 0; $i < $rows; $i++){
			$row = $items->fetch_array();
			$item = $row["Item_id"];
			$query = "update Items set Items.Quantity=Items.Quantity+1 where Items.Item_id = '$item'";
			$result = $db->query($query);	
		}

		# Delete item from buyer's cart
		$query = "delete from Cart where Cart.Buyer_id = $buyer";
		$result = $db->query($query);
	}

	# Unset the session
	$_SESSION = array();
	
	# Destroy the session
	session_destroy();

	# Take user back to log in page
	header("Location: http://localhost:8082/project2/login.html");

?>