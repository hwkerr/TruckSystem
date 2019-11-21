<?php

include "db_ninja.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: logon.php");
	exit;
}

$fname = $_GET['FName'];
$lname = $_GET['LName'];
$email = $_GET['Email'];

if ($_SESSION['UserType'] == 'Admin')
{
	$cid = $_GET['CompanyID'];
}
else if ($_SESSION['UserType'] == 'Sponsor')
{
	$cid = ninja_sponsor_company_id($_SESSION['UserID']);
}
else
{
	header("location: logon.php");
	exit;
}

$tpass = ninja_new_sponsor($fname, $lname, $email, $cid);
if (!$tpass)
{
	header("location: logon.php");
	exit;
}

include "mailer.php";
$mail->AddAddress($email, ''.$fname.' '.$lname);
$mail->Subject = "Your What the Truck! account has been created";
$mail->Body = "Greetings, new Sponsor for ".ninja_company_name($cid).", and welcome to the What the Truck! family!! \nLogin with this email and the temporary password '".$tpass."', which you will be prompted to change upon first logging in. Keep on trucking!";
$mail->Send();

header("location: logon.php");
exit;

?>
