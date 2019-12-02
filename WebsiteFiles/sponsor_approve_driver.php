<?php

include "db_ninja.php";
include "mailer.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Sponsor')
{
	header("location: DesktopSite.php");
	exit;
}

$did = $_GET['DID'];
$sid = $_SESSION['UserID'];
$cid = ninja_sponsor_company_id($sid);

if (ninja_driver_company_status($did, $cid) != 0)
{
	header("location: logon.php");
	exit;
}

ninja_company_accept_driver($cid, $did);

$email = ninja_email($did);
$fname = ninja_fname($did);
$lname = ninja_lname($did);
$cname = ninja_company_name($cid);

$mail->AddAddress($email, ''.$fname.' '.$lname);
$mail->Subject = "Your What the Truck! application with ".$cname."  has been approved";
$mail->Body = "Greetings, Driver, and welcome to the ".$cname." family!!\r\nYour application has been reviewed and approved. Congratulations!\r\n\r\nKeep on trucking!";
$mail->Send();

header("location: logon.php");

?>
