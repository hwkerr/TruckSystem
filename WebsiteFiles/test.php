<?php
	require("./vendor/phpmailer/phpmailer/src/PHPMailer.php");
	require("./vendor/phpmailer/phpmailer/src/SMTP.php");
 $mail = new PHPMailer\PHPMailer\PHPMailer(); 
$mail->Send(); 

?>
