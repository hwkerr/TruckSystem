<?php

include "../db_ninja.php";

$email = $_GET['email'] ?? '';

echo ninja_userid($email);

?>
