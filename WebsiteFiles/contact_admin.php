<?php

include "db_ninja.php";
session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
{
	header("location: logon.php");
	exit;
}

$message = "Contact an admin for assistance, and they will email you in support at their earliest convenience!";
$type = $_SESSION['UserType'];
$uid = $_SESSION['UserID'];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$fname = $_POST['FName'];
	$lname = $_POST['LName'];
	$email = $_POST['Email'];
	$info = $_POST['Info'];

	include "mailer.php";
	$aid = ninja_random_admin();
	$aemail = ninja_email($aid);
	$afname = ninja_fname($aid);
	$alname = ninja_lname($aid);
	$mail->AddAddress($aemail, ''.$afname.' '.$alname);
	$mail->Subject = "What the Truck! ".$type." help request";
	$mail->Body = "A What the Truck! ".$type.", ".$fname." ".$lname.", has requested assistance as follows:\r\n";
	$mail->Body .= "\r\n".$info."\r\n";
	$mail->Body .= "\r\nRespond to ".$email." with support.";
	$mail->Send();

	header("location: logon.php");
}
else
{
	$fname = $_SESSION['FName'];
	$lname = $_SESSION['LName'];
	$email = $_SESSION['Email'];
}

?>

<!DOCTYPE html>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body style = "height: 100%;background-image: linear-gradient(to bottom right, #071461, #0B358E);">
  <br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br /><?php echo $type ?>  Help<br/><hr/>
      </h1>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-lg" style = "text-align: center; color: white;">
          <p>
            <?php echo $message; ?>
            <br /><br /></p>
        </div>
      </div>
            <div class = "row justify-content-center" style = "margin: auto;">
              <div class = "col-lg" style = "text-align: center;">
                <form method = "post">
                  <div class = "row">
                    <div class = "col">
                      <input type = "text" name = "FName" class = "form-control" value = <?php echo $fname ?> placeholder = "Your First Name" />
                      </div>
                      <div class = "col">
                        <input type = "text" name = "LName" class = "form-control" value = <?php echo $lname ?> placeholder = "Your Last Name"/>
                        </div>
                    </div>
                    <div class = "row">
                      <div class = "col"><br />
                        <input type = "text" name = "Email" class = form-control value = <?php echo $email ?> placeholder = "Your Email" />
                        </div>
                      </div>
                      <div class = row>
                        <div class = "col">
                        <div class = "form-group">
                          <label for = "DriverorSponsor"></label>
                          </div>
                        </div>
                        </div>
                        <div class = "row">
                          <div class = "col">
                            <textarea name = "Info" class = "form-control" rows = "4" placeholder="Tell us about any issue you may be experiencing!"></textarea>
                          </div>
                        </div>
                        <div class = "row">
                          <div class = "col"><br />
                            <button type = submit class = "btn btn-outline-light btn-block">Submit</button>
              		    <a href = "logon.php" class = "form-text" style = "font-size: 12px; color: white;">Back to Home</a>
                          </div>
                        </div>
                  </form>
                <hr />
              </div>
            </div>
          </div>

</body>

</html>
