<?php

include "db_ninja.php";
include "mailer.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: DesktopSite.php");
	exit;
}

$uid = $_GET['UID'];
$fname = ninja_fname($uid);
$lname = ninja_lname($uid);
$email = ninja_email($uid);

ninja_delete_account($uid);

$mail->AddAddress($email, ''.$fname.' '.$lname);
$mail->Subject = "Your What the Truck! account has been deleted";
$mail->Body = "Goodbye, old friend, and good luck with the rest of your life from the What the Truck! family!! \nYour account has been reviewed and terminated. Do not login with this email, as it will no longer work. Keep on trucking!";
$mail->Send();

header("location: admin_view.php");

?>
