<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';
$street = $_GET['street'] ?? '';
$street2 = $_GET['street2'] ?? '';
$city = $_GET['city'] ?? '';
$state = $_GET['state'] ?? '';
$zip = $_GET['zip'] ?? '';

ninja_address_update($uid, $street, $street2, $city, $state, $zip);

include 'address.php';

?>
