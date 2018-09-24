<?php
	session_start();
	
	# Connet to DB
	$db = new mysqli('localhost', 'root', 'secret', 'store');
	      if ($db->connect_error):
	         die ("Could not connect to db: " . $db->connect_error);
	      endif;

	# Authenticate user login and store user info in session var
	if(!isset($_SESSION["ownerloggedin"]) || $_SESSION["ownerloggedin"] == 0){ # Check to see if the user is already logged in
		$enteredEmail = $_POST["email"];
		$enteredPass = $_POST["password"];
		$query = "select * from Owners where Owners.Email = '$enteredEmail' and Owners.Password = '$enteredPass'";
		$result = $db->query($query);
		$rows = $result->num_rows;
		if ($rows != 1){ # check if a single match was not found
				echo "<h1 align=\"center\">Invalid Login</h1>";
				echo "<h3 align=\"center\"><a href=\"http://localhost:8082/project2/login.html\">Login Page</a></h3>";
		}else{
			$row = $result->fetch_array(); # get authenticated user info
			$_SESSION["name"] = $row["Name"];
			$_SESSION["email"] = $_POST["email"];
			$_SESSION["owner"] = $row["Owner_id"];
			$_SESSION["earned"] = $row["Earned"];
			$_SESSION["ownerloggedin"] = 1;
			$_SESSION["buyerloggedin"] = 0;
			header("Refresh:0");
		}
	} else {
	# create form and show earnings
	
?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Owner Page</title>
		</head>
		<body>
			<h4 align="right"><form action="logout.php"><input type="submit" value="Logout"></input></form></h4>
			<?php
				echo "<h2>Total Earned by " . $_SESSION["name"] . ": " . $_SESSION["earned"] . "</h2>" #earnings
			?>
			<br />
			<!--
				create form to allow owner to add an item
			-->
			<h2>Fill out this form to add an item.</h2>
			<form action = "additem.php" method = "POST">
				<b>Item Name: </b>
				<input type = "text" name = "itemname" size = "30" maxlength = "30"></input>
				<br /><br />
				<b>Item Category: </b>
					<select name="category">
						<option value=1>Books</option>
						<option value=0>Flowers</option>
						<option value=2>Cars</option>
					</select>
				<br /><br />
				<b>Item Description: </b>
				<input type = "text" name = "description" size = "30" maxlength = "30"></input>
				<br /><br />
				<b>Item Cost: </b>
				<input type = "text" name = "cost" size = "30" maxlength = "30"></input>
				<br /><br />
				<b>Number of Item: </b>
				<input type = "text" name = "quantity" size = "30" maxlength = "30"></input>
				<br /><br />
				<input type = "submit" value = "Submit">
			</form>
		</body>
	</html>
<?php
}
?>