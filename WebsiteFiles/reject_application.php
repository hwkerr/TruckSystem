<?php

include "db_ninja.php";
include "mailer.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: DesktopSite.php");
	exit;
}

$fname = $_GET['FName'];
$lname = $_GET['LName'];
$email = $_GET['Email'];

ninja_reject_email_application($email);

$mail->AddAddress($email, ''.$fname.' '.$lname);
$mail->Subject = "Your What the Truck! application has been rejected";
$mail->Body = "Greetings, applicant, and consolations from the What the Truck! family. \nYour application has been reviewed and rejected. Keep on trucking!";
$mail->Send();

header("location: admin_view.php");

?>
