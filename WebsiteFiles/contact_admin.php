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
<html>
<?php include "htmlhead.php"?>
<body style = "height: 100%;">
 <?php include "driver_header.php"?>
<div class = "jumbotron">
<h1>Driver Help</h1>
</div>
<div class = "container-fluid" style = "margin: 0 auto;"><!--
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center;">
      <h1>
        <br /><?php echo $type ?>  Help<br/><hr/>
      </h1>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-lg" style = "text-align: center;">
          <p>
            <?php echo $message; ?>
            <br /><br /></p>
        </div>
      </div>-->
            <div class = "row justify-content-center" style = "margin: auto;">
	      <div class = "col-lg-8">
		<p style = "font-size: 36px"><p class = "lead">Here at What the Truck!, we believe that the customer comes first!</p> That's why we think it is very Trucking important the you are always able to get in touch with our admins. Whether you are having issues with the website or you just want to chat, place your message and information in the form provided and a What the Truck representative will get back to you as soon as possible. We look forward to hearing from you! Keep on Truckin!</p>
	<footer class = "blockquote-footer">Harrison Kerr, Founder of What the Truck!</footer>
		</div>
	      </div><br><br>
		<div class = "row justify-content-center" style = "margin: auto;">
              <div class = "col-lg-8">
                <form method = "post">
                  <div class = "row">
                    <div class = "col">
		      <label for = "FirstName">First Name</label>
                      <input type = "text" id = "FirstName" name = "FName" class = "form-control" value = <?php echo $fname ?> placeholder = "Your First Name" />
                      </div>
                      <div class = "col">
			<label for = "LastName">Last Name</label>
                        <input type = "text" id = "LastName" name = "LName" class = "form-control" value = <?php echo $lname ?> placeholder = "Your Last Name"/>
                        </div>
                    </div>
                    <div class = "row">
                      <div class = "col"><br />
			<label for = "PEmail">Email</label>
                        <input type = "text" id = "PEmail" name = "Email" class = form-control value = <?php echo $email ?> placeholder = "Your Email" />
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
                            <button type = submit class = "btn btn-primary btn-block">Submit</button>
              		    <a href = "logon.php" class = "form-text" style = "font-size: 12px; text-align: center;">Back to Home</a>
                          </div>
                        </div>
                  </form>
               </div>
              </div>
            </div>
          </div>

</body>

</html>
