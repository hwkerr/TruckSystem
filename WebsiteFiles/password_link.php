<?php

include "../inc/dbinfo.inc";
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

session_start();
$_SESSION = array();
session_destroy();

$pst = $db->prepare("SELECT Email FROM Account WHERE UserID = ?");
$uid = $_GET['UserID'];
$pst->bind_param("s", $uid);
$pst->execute();
$res =  $pst->get_result();
$res->data_seek(0);
$email = "";
if ($row = $res->fetch_assoc())
{
	$email = $row['Email'];
}

session_start();
$_SESSION['UserID'] = $uid;
$_SESSION['Logged'] = true;
$_SESSION['Email'] = $email;
$_SESSION['UserType'] = "Driver";
header("location: change_password.php");

?>
