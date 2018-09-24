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

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Checkout</title>
		<h1 align="center">Checkout Summary</h1>
	</head>
	<body>
		<!--
			Create back to store button for more shopping and log out button that will direct user to logout.php
		-->
		
		<h4 align="right"><form action="buyer.php"><input type="submit" value="Back to Store"></input></form></h4>
		<h4 align="right"><form action="logout.php"><input type="submit" value="Logout"></input></form></h4>
		<br />

		<!--
			Create summary table
		-->
		<table border="1" style="width:60%" align="center">
			<tr>
			   <th>Product</th>
			   <th>Price</th> 
			</tr>
			<?php
				$totalcost = 0;
				$buyer = $_SESSION["buyerid"];
				# Get info about items in buyer's cart to process and display
				$query1 = "select Items.Name, Items.Cost, Items.Owner_id, Owners.Email from Items, Cart, Owners where Cart.Buyer_id = $buyer and Cart.Item_id = Items.Item_id and Items.Owner_id = Owners.Owner_id";
				$result1 = $db->query($query1);
				$rows = $result1->num_rows;

				# Set up mailer to send confirmation email
				$mailpath = 'C:\PHPMailer-master';
					$path = get_include_path();
					set_include_path($path . PATH_SEPARATOR . $mailpath);
					//require 'PHPMailerAutoload.php';
					include_once 'PHPMailerAutoload.php';
					$mail = new PHPMailer();
					  $mail->IsSMTP(); // telling the class to use SMTP
					  $mail->SMTPAuth = true; // enable SMTP authentication
					  $mail->SMTPSecure = "tls"; // sets tls authentication
					  $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server; or your email service
					  $mail->Port = 587; // set the SMTP port for GMAIL server; or your email server port
					  $mail->Username = "cs4501.fall15@gmail.com"; // email username
					  $mail->Password = "UVACSROCKS"; // email password

				for ($i = 0; $i < $rows; $i++): // for each value in the cart, display the name and price
					$row = $result1->fetch_array();
					echo "<tr align = \"center\">";
					echo "<td>" . $row["Name"] . "</td>";
					echo "<td>" . $row["Cost"] . "</td>";
					echo "</tr>";

					# Update total cost
					$cost = $row["Cost"];
					$totalcost += $cost;

					# Update amount earned by Item's owner
					$ownerid = $row["Owner_id"];
					$query2 = "update Owners set Owners.Earned = Owners.Earned+'$cost' where Owners.Owner_id = '$ownerid'";
					$result2 = $db->query($query2);

					# Set values to send the email to owner based on sender and item
					  $sender = $_SESSION["email"];
					  $receiver = $row["Email"];
					  $subj = "Items Bought From Store";
					  $msg = "Item " . $row["Name"] . " was bought from the store for $" . $cost . " by " . $_SESSION["name"];

					  // Put information into the message
					  $mail->addAddress($receiver);
					  $mail->SetFrom($sender);
					  $mail->Subject = "$subj";
					  $mail->Body = "$msg";

					  // Send message
					  $mail->send();
					  $mail->ClearAddresses();
				endfor;

				# Delete item from user's cart because it is now purchased
				$query2 = "delete from Cart where Cart.Buyer_id = $buyer";
				$result2 = $db->query($query2);
			?>
		</table>
		<br />
		<?php
			# Display total and address
			echo "<h4 align=\"center\">Thank you, " . $_SESSION["name"] . ". Your total is $" . $totalcost ." and your items will be mailed to " . $_SESSION["address"] . ".";
		?>
	</body>
</html>