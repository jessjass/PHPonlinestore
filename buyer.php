<?php
	session_start();

	# Connet to DB
	$db = new mysqli('localhost', 'root', 'secret', 'store');
	      if ($db->connect_error):
	         die ("Could not connect to db: " . $db->connect_error);
	      endif;

	# Authenticate user login and store user info in session var
	if(!isset($_SESSION["buyerloggedin"]) || $_SESSION["buyerloggedin"] == 0){ # Check to see if the user is already logged in
		$enteredEmail = $_POST["email"];
		$enteredPass = $_POST["password"];
		$query = "select * from Buyers where Buyers.Email = '$enteredEmail' and Buyers.Password = '$enteredPass'";
		$result = $db->query($query);
		$rows = $result->num_rows;
		if ($rows != 1){ # check if a single match was not found
				echo "<h1 align=\"center\">Invalid Login</h1>";
				echo "<h3 align=\"center\"><a href=\"http://localhost:8082/project2/login.html\">Login Page</a></h3>";
		}else{
			$row = $result->fetch_array(); # get authenticated user info and store in session var
			$_SESSION["name"] = $row["Name"];
			$_SESSION["email"] = $_POST["email"];
			$_SESSION["address"] = $row["Address"];
			$_SESSION["buyerid"] = $row["Buyer_id"];
			$_SESSION["ownerloggedin"] = 0;
			$_SESSION["buyerloggedin"] = 1;
			header("Refresh:0");
		}
	} else {
		# create a web page with store items
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Buyer Page</title>
	</head>

	<body>
		<h4 align="right"><form action="logout.php"><input type="submit" value="Logout"></input></form></h4>
		<?php
			echo "<h1>Welcome to the online store, " . $_SESSION["name"] . "!</h1><br />";

			# Determine which category to present
			if (!isset($_SESSION["category"])){ # if category session is not set go to defualt category
				echo "You're viewing the Flowers category";
				$_SESSION["category"] = 0;
			} else {
				if ($_SESSION["category"] == 0){
					echo "You're viewing the Flowers category";
				}elseif ($_SESSION["category"] == 1){
					echo "You're viewing the Books category";
				}elseif ($_SESSION["category"] == 2){
					echo "You're viewing the Cars category";
				}
			}

			# Count number of items in the buyers cart and display
			$buyer = $_SESSION["buyerid"];
			$result = $db->query("select * from Cart where Cart.Buyer_id = '$buyer'");
			$rows = $result->num_rows;
			echo "<h4 align=\"right\">Number of items in cart: " . $rows . "</h4><br />";

			# Display items of the appropriate category
			$cat = $_SESSION["category"];
			$result = $db->query("select * from Items where Items.Cat = '$cat'");
			$rows = $result->num_rows;

			# Create a table to display items
		?>

		<table border = "1" align="center" style="width:80%">
      		<tr align = "center">
      			<th>Item</th>
      			<th>Description</th>
      			<th>Price</th>
      			<th>Number Left</th>
      			<th></th>
      		</tr>
			<?php
		        for ($i = 0; $i < $rows; $i++):  # For each item 
		            $row = $result->fetch_array();  # Get that item
			        if ($row["Quantity"] > 0){ # If there are actual items to display, show the item
			             echo "<tr align = \"center\">";
			             echo "<td>" . $row["Name"] . "</td>";
			             echo "<td>" . $row["Description"] . "</td>";
			             echo "<td> $" . $row["Cost"] . "</td>";
			             echo "<td>" . $row["Quantity"] . "</td>";
			             $itemid = $row["Item_id"];
			             echo "<td><form action = \"addcart.php\" method = \"POST\"><button name=\"add\" type=\"submit\" value=$itemid>Add to Cart</button></form></td>";
			             echo "</tr>";
		         	}
		         endfor;
		    ?>
		    <tr align = "center">
		    	<td></td>
		    	<td>Item Category: 
		    		<form action="catchange.php" method="POST">
			    		<select name="category">
							<option value=1>Books</option>
							<option value=0>Flowers</option>
							<option value=2>Cars</option>
						</select>
						<input type="submit" value="Change Category"></input>
					</form>
		    	</td>
		    	<td><form action="checkout.php" method="POST"><input type="submit" value="Checkout"></input></form></td>
		    	<td></td>
		    	<td></td>
		</table>
	</body>
</html>
<?php
	}
?>