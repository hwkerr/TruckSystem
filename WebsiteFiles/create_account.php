<?php

include "db_ninja.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: DesktopSite.php");
	exit;
}

$type = $_POST['UserType'];
$fname = $_POST['FName'];
$lname = $_POST['LName'];
$email = $_POST['Email'];

if ($type == 'Driver')
{
	header("location: approve_driver.php?FName=".$fname."&LName=".$lname."&Email=".$email);
	exit;
}
else if ($type == 'Sponsor')
{
	$cid = $_POST['CompanyID'];
	header("location: create_sponsor.php?FName=".$fname."&LName=".$lname."&Email=".$email."&CompanyID=".$cid);
	exit;
}

header("location: admin_view.php");
exit;

?>
