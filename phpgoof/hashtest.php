<?php
$pw = "beefsteak";
$ph = password_hash($pw, PASSWORD_BCRYPT);
echo $pw . "\n";
echo $ph . "\n";
$pv = password_verify($pw, $ph);
print_r($pv . "\n");
$pv = password_verify("notpassword", $ph);
print_r($pv . "\n");
?>
