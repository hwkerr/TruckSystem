<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$iid = $_GET['ItemID'];
$cid = $_GET['CatalogID'];
$price = $_GET['Price'];

if (!isset($_SESSION['Cart']))
{
	$_SESSION['Cart'] = array();
}

array_push($_SESSION['Cart'], array("ItemID"=>$iid, "Price"=>$price, "CatalogID"=>$cid));

header("location: logon.php");

?>
