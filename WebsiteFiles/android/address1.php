<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';

echo ninja_address_oneline($uid);

?>
