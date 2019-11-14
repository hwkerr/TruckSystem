<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}

if (!isset($_SESSION['Cart']) || count($_SESSION['Cart']) < 1)
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$cart = $_SESSION['Cart'];

ninja_place_order($uid, $cart);

$_SESSION['Cart'] = array();

header("location: logon.php");

?>
