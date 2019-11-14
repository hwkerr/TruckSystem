<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';

$street = ninja_address_street($uid);
$street2 = ninja_address_street2($uid);
$city = ninja_address_city($uid);
$state = ninja_address_state($uid);
$zip = ninja_address_zip($uid);

echo $street . "\n" . $street2 . "\n" . $city . "\n" . $state . "\n" . $zip;

?>
