<!DOCTYPE html>
<html>
 <head>
  <title>Set Up DB</title>
 </head>
 <body>
 <?php
      # Connect to the DB
      $db = new mysqli('localhost', 'root', 'secret', 'store');
      if ($db->connect_error):
         die ("Could not connect to db: " . $db->connect_error);
      endif;

      # Drop tables in case they already have infromation stored

      $db->query("drop table Items"); 
      $db->query("drop table Owners");
      $db->query("drop table Buyers");
      $db->query("drop table Cart");

      # Create the tables
      $result = $db->query(
                "create table Items (Name varchar(30) not null, Cat int not null, Description varchar(30) not null, Cost float not null, Quantity int not null, Owner_id int not null, Item_id int not null auto_increment primary key)") or die ("Invalid: " . $db->error);
      $items = file("Items.flat");
      //print_r($items);

      # Split each element from the items array and inster into appropriate tables

      # Fill Items tables
      foreach ($items as $itemstring)
      {
          $itemstring = rtrim($itemstring);
          $item = explode("|", $itemstring);
          $query = "insert into Items values ('$item[0]','$item[1]','$item[2]','$item[3]','$item[4]','$item[5]', NULL)";
          $db->query($query) or die ("Invalid insert " . $db->error);
      }
      
      # Fill Owners table
      $result = $db->query(
                "create table Owners (Name varchar(30) not null, Password char(30) not null, Email varchar(30) not null, Earned float not null, Owner_id int not null auto_increment primary key)") or die ("Invalid: " . $db->error);
      $owners = file("Owners.flat");
      foreach ($owners as $ownerstring)
      {
          $ownerstring = rtrim($ownerstring);
          $owner = explode("|", $ownerstring);
          $query = "insert into Owners values ('$owner[0]', '$owner[1]', '$owner[2]', '$owner[3]', NULL)";
          $db->query($query) or die ("Invalid insert " . $db->error);
      } 


      # Fill Buyers table
      $result = $db->query(
                "create table Buyers (Name varchar(30) not null, Email varchar(30) not null, Password char(30) not null, Address varchar(60) not null, Buyer_id int not null auto_increment primary key)") or die ("Invalid: " . $db->error);
      $buyers = file("Buyers.flat");
      foreach ($buyers as $buyerstring)
      {
          $buyerstring = rtrim($buyerstring);
          $buyer  = explode("|", $buyerstring);
          $query = "insert into Buyers values ('$buyer[0]','$buyer[1]', '$buyer[2]','$buyer[3]', NULL)";
          $db->query($query) or die ("Invalid insert " . $db->error);
      } 

      $result = $db->query("create table Cart (Buyer_id int not null, Item_id int not null)");

      # Go to login page
      header("Location: http://localhost:8082/project2/login.html");
?>
 </body>
</html>