<?php

include "db_ninja.php";
include "mailer.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: logon.php");
	exit;
}

$fname = $_GET['FName'];
$lname = $_GET['LName'];
$email = $_GET['Email'];

$tpass = ninja_new_driver($fname, $lname, $email);
if (!$tpass)
{
	header("location: logon.php");
	exit;
}

ninja_mark_email_application_processed($email);

$mail->AddAddress($email, ''.$fname.' '.$lname);
$mail->Subject = "Your What the Truck! account has been created";
$mail->Body = "Greetings, new Driver, and welcome to the What the Truck! family!! \nYour application has been reviewed and approved. Login with this email and the temporary password '".$tpass."', which you will be prompted to change upon first logging in. Keep on trucking!";
$mail->Send();

header("location: admin_view.php");

?>
