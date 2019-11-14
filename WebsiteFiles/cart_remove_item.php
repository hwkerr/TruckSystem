<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}

if (!isset($_SESSION['Cart']))
{
	header("location: logon.php");
	exit;
}

$element = $_GET['Element'];
array_splice($_SESSION['Cart'], $element, 1);

header("location: driver_cart.php");

?>
