<?php
	include "../inc/mailinfo.inc";
	require("./vendor/phpmailer/phpmailer/src/Exception.php");
	require("./vendor/phpmailer/phpmailer/src/PHPMailer.php");
	require("./vendor/phpmailer/phpmailer/src/SMTP.php");
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->CharSet = "utf-8";
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Username = SEND_NAME;
			$mail->Password = SEND_PASS;
			$mail->SMTPSecure = "ssl";
			$mail->Host = "smtp.gmail.com";
			$mail->Port = "465";
			$mail->From = SEND_NAME;
			$mail->FromName = "What the Truck!";
?>
