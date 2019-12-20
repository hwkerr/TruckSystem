<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Sponsor')
{
	header("location: DesktopSite.php");
	exit;
}

$did = $_GET['DID'];
$sid = $_SESSION['UserID'];
$cid = ninja_sponsor_company_id($sid);
if (ninja_driver_company_status($did, $cid) != 1)
{
	header("location: logon.php");
	exit;
}

$points = $_GET['PointAdd'];

ninja_add_points($did, $sid, $points);

if (ninja_point_alert($did))
{
	include "mailer.php";

	$email = ninja_email($did);
	$fname = ninja_fname($did);
	$lname = ninja_lname($did);
	$cname = ninja_company_name($cid);
	
	$mail->AddAddress($email, ''.$fname.' '.$lname);
	$mail->Subject = "You got some new What the Truck! points with ".$cname."!";
	$mail->Body = "Greetings, Driver, and congratulations on your points!!\r\nYou now have ".$points." new points with ".$cname." to spend on amazing rewards!\r\n\r\nKeep on trucking!";
	$mail->Send();
}

header("location: logon.php");

?>
