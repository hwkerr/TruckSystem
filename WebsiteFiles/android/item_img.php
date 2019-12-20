<?php

include "../db_ninja.php";

$itemid = $_GET['itemid'] ?? '';
$catid = $_GET['catid'] ?? '';

$img = ninja_item_image($itemid, $catid);

echo $img;

?>
