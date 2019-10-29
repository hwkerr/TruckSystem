<?php

include "../db_ninja.php";

$username = $_GET['userID'];
$password = $_GET['password'];

echo ninja_login($username, $password);

?>
