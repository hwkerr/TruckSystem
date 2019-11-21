<?php

include "../db_ninja.php";

$itemid = $_GET['itemid'] ?? '';
$catid = $_GET['catid'] ?? '';

echo ninja_item_description($itemid, $catid);

?>
