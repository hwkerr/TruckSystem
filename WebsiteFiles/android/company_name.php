<?php

include "../db_ninja.php";

$cid = $_GET['cid'] ?? '';

echo ninja_company_name($cid);

?>
