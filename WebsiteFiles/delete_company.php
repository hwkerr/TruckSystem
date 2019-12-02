<?php

include "db_ninja.php";
include "mailer.php";

session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: DesktopSite.php");
	exit;
}

$cid = $_GET['CID'];

$employees = ninja_company_sponsor_list($cid);

ninja_delete_company($cid);

while ($row = $employees->fetch_assoc())
{
	$email = $row['Email'];
	$fname = $row['FName'];
	$lname = $row['LName'];
	$mail->AddAddress($email, ''.$fname.' '.$lname);
}
$mail->Subject = "Your What the Truck! company has been deleted";
$mail->Body = "Goodbye, old friend, and good luck with the rest of your life from the What the Truck! family!! \nYour sponsor company, and by extension your account, has been terminated. Do not login with this email, as it will no longer work. Keep on trucking!";
$mail->Send();


header("location: admin_view.php");

?>
