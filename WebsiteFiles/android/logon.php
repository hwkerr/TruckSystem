<?php

include "../db_ninja.php";

$email = $_GET['email'] ?? '';
$password = $_GET['password'] ?? '';

echo ninja_login($email, $password);

?>
