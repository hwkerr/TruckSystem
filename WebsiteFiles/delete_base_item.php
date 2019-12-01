<?php

include "db_ninja.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: logon.php");
	exit;
}

$iid = $_GET['ItemID'];

ninja_delete_base_item($iid);

if (isset($_GET['CatalogID']))
	header("location: base_catalog.php?CatalogID=".$_GET['CatalogID']);
else
	header("location: base_catalog.php");

?>
