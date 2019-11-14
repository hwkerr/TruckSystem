<?php
	include "../inc/dbinfo.inc";
	include "mailer.php";
	
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
<?php include "htmlhead.php"?>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">
  <br /><br />
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
