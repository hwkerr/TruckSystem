<?php

include "db_ninja.php";
include "mailer.php";

$message = 'We are honored that you want to join the What the Truck family! To get started with us, please tell us a bit about yourself and intrested in either a driving position or if you are representing a company. <br />Thanks!';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$fname = $_POST['FName'];
	$lname = $_POST['LName'];
	$email = $_POST['Email'];
	$type = $_POST['DriverSponsor'];
	$info = $_POST['Info'];
	$status = ninja_apply($type, $fname, $lname, $email, $info);
	if ($status === 0)
	{
		$message = "Application submitted!";
		$mail->AddAddress($email, ''.$fname.' '.$lname);
		$mail->Subject = "Your What the Truck! application has been submitted";
		$mail->Body = "Greetings, prospective ".$type."!\nYour application has been submitted for review. When our admins have finished processing it, you will receive an email informing you of the result.";
		$mail->Send();
	}
	else if ($status === 1)
	{
		$message = "An account with this email already exists! If you have forgotten your password, use the password reset link on the login screen to recover it.";
	}
	else if ($status === 2)
	{
		$message = "An applicaion with this email address already exists! Please wait for our admins to process your application.";
	}
	else
	{
		$message = "An unknown error occurred; your application was not submitted.";
	}
}

?>

<!DOCTYPE html>
<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body style = "height: 100%;background-image: linear-gradient(to bottom right, #071461, #0B358E);">
  <title>Login</title><br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br />Welcome!<br/><hr/>
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
                      <input type = "text" name = "FName" class = "form-control" placeholder = "First Name" />
                      </div>
                      <div class = "col">
                        <input type = "text" name = "LName" class = "form-control" placeholder = "Last Name"/>
                        </div>
                    </div>
                    <div class = "row">
                      <div class = "col"><br />
                        <input type = "text" name = "Email" class = form-control placeholder = "Email" />
                        </div>
                      </div>
                      <div class = row>
                        <div class = "col">
                        <div class = "form-group">
                          <label for = "DriverorSponsor"></label>
                          <select name = "DriverSponsor" class = "form-control" id = "DriverorSponsor">
                            <option>
                              Driver
                              </option>
                              <option>
                                Sponsor
                              </option>
                          </select>
                          </div>
                        </div>
                        </div>
                        <div class = "row">
                          <div class = "col">
                            <textarea name = "Info" class = "form-control" rows = "4" placeholder="Tell us anything about yourself that we should know!"></textarea>
                          </div>
                        </div>
                        <div class = "row">
                          <div class = "col"><br />
                            <button type = submit class = "btn btn-outline-light btn-block">Submit</button>
              		    <a href = "DesktopSite.php" class = "form-text" style = "font-size: 12px; color: white;">Back to Home</a>
                          </div>
                        </div>
                  </form>
                <hr />
              </div>
            </div>
          </div>

</body>

</html>
