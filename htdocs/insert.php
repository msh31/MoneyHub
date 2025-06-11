<?php
require "db/dbconnection.class.php";
$dbconnect = new dbconnection();
//kijken of er submit is geplaatst
if(isset($_POST['itemName']))
{
  $name = $_POST['itemName'];
  $number = $_POST['amount'];
  $type = $_POST['type'];
  $category = $_POST['category'];
  $period = $_POST['period'];
  $sql = "INSERT INTO items (item_name, amount, type, category_id, period_id) VALUES ('$name', '$number', '$type', '$category', '$period')";
  $query = $dbconnect -> prepare($sql);
  $query -> execute() ;
}
header('location: index.php');