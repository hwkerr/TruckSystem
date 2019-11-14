<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';
$phone = $_GET['phone'] ?? '';

ninja_update_phone($uid, $phone);

include 'phone.php';

?>
