<?php

include "../inc/dbinfo.inc";
include "db_ninja.php";

$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

session_start();
$_SESSION = array();
session_destroy();

$pst = $db->prepare("SELECT Email, FName, LName FROM Account WHERE UserID = ?");
$uid = $_GET['UserID'];
$pst->bind_param("s", $uid);
$pst->execute();
$res =  $pst->get_result();
$res->data_seek(0);
$email = "";
$fname = "";
$lname = "";
if ($row = $res->fetch_assoc())
{
	$email = $row['Email'];
	$fname = $row['FName'];
	$lname = $row['LName'];
}

session_start();
$_SESSION['UserID'] = $uid;
$_SESSION['Logged'] = true;
$_SESSION['Email'] = $email;
$_SESSION['UserType'] = ninja_user_type($uid);
$_SESSION['FName'] = $fname;
$_SESSION['LName'] = $lname;
header("location: change_password.php");

?>
