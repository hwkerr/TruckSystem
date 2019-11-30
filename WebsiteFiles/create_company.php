<?php

include "db_ninja.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: logon.php");
	exit;
}

$cname = $_POST['Name'];

$tpass = ninja_create_empty_company($cname);

header("location: logon.php");
exit;

?>
