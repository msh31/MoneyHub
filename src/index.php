<?php
echo "<a href='index2.php'>link naar formulier</a>";

require "db/dbconnection.class.php";
$dbconnect = new dbconnection();
$sql = "SELECT * FROM items LEFT JOIN categories ON categories.id = category_id LEFT JOIN periods ON periods.id = period_id";
$query = $dbconnect -> prepare($sql);
$query -> execute() ;

//hier sla je alle records die je uit de database opgevraagd hebt, op in de array $recset ('recset' is een afkorting voor records-set - een andere naam mag ook);
//‘fetchAll()’ is een functie binnen PDO en betekent letterlijk: trek (fetch) alles (all) uit de database op basis van de query;
//$recset is een array met gevonden records uit de database
$recset = $query -> fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($recset);
echo "</pre>";