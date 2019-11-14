<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';
$fname = $_GET['fname'] ?? '';
$lname = $_GET['lname'] ?? '';

ninja_update_name($uid, $fname, $lname);

include 'name.php';

?>
