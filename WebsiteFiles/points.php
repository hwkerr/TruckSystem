<?php
$link = new mysqli('database-1.c3icyvcxw6xg.us-east-1.rds.amazonaws.com', 'admin', 'beefsteak', 'trucks');
if (!$link) 
{
	die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully to:' . "\n";
echo $link->host_info . "\n";

$driverID = $_POST['ID'];
$total = 0;
$st = $link->prepare("SELECT SUM(Amount) AS Total FROM DriverPointAddition WHERE DriverID = ?");
$st->bind_param("s", $driverID);
$st->execute();
$res = $st->get_result();
$res->data_seek(0);
if ($row = $res->fetch_assoc())
{
	$total = $row['Total'];
}

$st = $link->prepare("SELECT SUM(PointPrice) AS Total FROM OrderCatalogItem INNER JOIN Order ON Order.OrderID = OrderCatalogItem.OrderID WHERE Order.DriverID = ?");
$st->bind_param("s", $driverID);
$st->execute();
$res = $st->get_result();
$res->data_seek(0);
if ($row = $res->fetch_assoc())
{
	$total = $total - $row['Total'];
}

$st->close();
mysqli_close($link);
?>
