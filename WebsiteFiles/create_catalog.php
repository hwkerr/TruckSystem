<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: DesktopSite.php");
	exit;
}

$cid = $_POST['CID'];
$name = $_POST['NewCatalogName'];

ninja_new_catalog($cid, $name);

header("location: logon.php");

?>
