<?php

include "db_ninja.php";
session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: DesktopSite.php");
	exit;
}

$uid = $_SESSION['UserID'];
$cid = $_GET['CID'];

ninja_driver_apply_company($uid, $cid);

header("location: driver_sponsor_list.php");

?>
