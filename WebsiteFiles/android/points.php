<?php

include "../db_ninja.php";

$uid = $_GET['uid'] ?? '';
$cid = $_GET['cid'] ?? '';

echo ninja_points($uid, $cid);

?>
