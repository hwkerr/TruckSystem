<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: logon.php");
	exit;
}

$cid = $_GET['CatalogID'];
$items = ninja_catalog_items($cid);

?>

<html>
<?php include"htmlhead.php" ?>
<body>
<div class = "jumbotron" style = "margin:0;"> 
  <h1>Catalog Info</h1>
</div>
<table class = "table">
<thead>
   <th>Item Number</th>
   <th>Item Name</th>
   <th>Item Point Value</th>
   <th>Item Quantity</th>
   <th>Edit Item</th>
   <th>Remove Item</td>
</thead>
<tr>
	<td>1</td>
	<td>Moon Shoes</td>
	<td>10000</td>
	<td>1</td>
	<td><a href = "edit_item.php">Link</a></td>
	<td>Drop Item</td>
</table><div style = "text-align: center;">
<button class = "btn btn-primary" onclick = "location.href = 'create_item.php' ">Add New Item</button></div>
</body>
</html>
