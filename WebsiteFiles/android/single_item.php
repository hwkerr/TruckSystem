<?php

include "../db_ninja.php";

$cid = $_GET['cid'];

$item = ninja_browse_catalog_items($cid)->fetch_assoc();

echo json_encode($item);

?>
