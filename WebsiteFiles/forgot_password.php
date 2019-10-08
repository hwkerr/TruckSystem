<?php
	include "../inc/dbinfo.inc";
	include "../inc/mailinfo.inc";
	require("./vendor/phpmailer/phpmailer/src/Exception.php");
	require("./vendor/phpmailer/phpmailer/src/PHPMailer.php");
	require("./vendor/phpmailer/phpmailer/src/SMTP.php");
	
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
	$error = "Enter the email associated with your account and we will send you an email with a link to reset your password.";
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ID"]))
	{
		$st = $db->prepare("SELECT Email FROM Account WHERE Email = ?");
		$email = mysqli_real_escape_string($db, $_POST['ID']);
		$st->bind_param("s", $email);
		$st->execute();
		$res = $st->get_result();
		$res->data_seek(0);
		if ($row = $res->fetch_assoc())
		{
			$uid = "";	
			$pst = $db->prepare("SELECT UserID FROM Account WHERE Email = ?");
			$pst->bind_param("s", $email);
			$pst->execute();
			$res =  $pst->get_result();
			$res->data_seek(0);
			if ($row = $res->fetch_assoc())
			{
				$uid = $row['UserID'];
			}
		
			//send email
			$link = "<a href='http://ec2-54-234-169-204.compute-1.amazonaws.com/password_link.php?UserID=".$uid."'>Click To Reset password</a>";
			//mail($email, "What the Truck! Password Reset", $link);
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
			$mail->AddAddress($email, 'Valued Customer');
			$mail->Subject = 'Reset your What the Truck! password';
			$mail->IsHTML(true);
			$mail->Body = "Click on the link below to reset your password: \r\n".$link." \r\nIf you did not request this change, safely ignore this email.";
			if ($mail->Send())
			{
				$error = "Reset link sent to $email!";
			}
			else
			{
				$error = "Error sending email: ".$mail->ErrorInfo."";
			}
		}
		else
		{
			//invalid email
			$error = "There is no account associated with this email address.";
		}
	}
?>

<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">
  <title>Login</title><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color:white;">
      <h1>
        <br />Forgot Password<br/><hr/>
      </h1>
    <br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
        <p style = "text-align: center; color: white;">
          <?php echo $error ?>
          </p>
          <form style = "margin: 0 auto;" method = "post">
            <div class = "form-group" style = "margin-center: auto;">
              <input type= "User-ID" name = "ID" class = "form-control" id = "InputID" placeholder="Enter Email"/>
            </div>
              <div class = "col-lg-6" style = "text-align: center;">
                <button type = "submit" class = "btn btn-outline-light btn-block">Send Email</button>
              </div>
          </form>
        </div>
      </div>
            <div class = "row justify-content-center" style = "margin: auto;">
            </div>
          </div>

</body>

</html>
