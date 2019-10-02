<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Change Password</h1>
<?php
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$email = mysqli_real_escape_string($db, $_POST['ID']);
		$pword = mysqli_real_escape_string($db, $_POST['Password']);
		$pst = $db->prepare("SELECT PassHash, TempPass, UserID FROM
				     Account WHERE Email = ?");
		$pst->bind_param("s", $email);
		$pst->execute();
		$res =  $pst->get_result();
		$res->data_seek(0);
		if ($row = $res->fetch_assoc())
		{
			if (password_verify($pword, $row['PassHash']))
			{
				//session_register($row['UserID']);
				//$_SESSION['login_user'] = $row['UserID'];
				header("location: DriverHome.html");
				$error = 'in';
			}
			else
			{
				$error = 'out';
			}
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
<body style = "height: 100%;background-image: linear-gradient(to bottom right, #071461, #0B358E);">
  <title>Login</title><br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br />Log In<br/><hr/>
      </h1>
    </br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
          <form style = "margin: 0 auto;" method = "post">
            <div class = "form-group" style = "margin-center: auto;">
              <input type= "text" name = "ID" class = "form-control" id = "InputID" placeholder="Enter User ID"/>
            </div>
            <div class = "form-group">
              <input type = "password" name = "Password" class = "form-control" id = "InputPassword" placeholder = "Enter Password" />
              <a href = "ForgotPassword.html" class = "form-text" style = "font-size: 12px; color: white;">Forgot Password?</a>
            </div>
            <div class = "Incorrectpassword" style = "display: none;">
              <p style = "color:red;">
                Error: Username or Password does not match
              </p>
            </div>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-outline-light btn-block">Log In</button>
            </div>
          </form>
        </div>
      </div>

      <br />
            <!--<div class = "row justify-content-center" style = "margin: auto;">
              <div class = "col-lg-6" style = "text-align: center;">
                <button type = "button" class = "btn btn-outline-light">Driver Login</button>
                <button type = "button" class = "btn btn-outline-light">Sponsor Login</button>
                <button type = "button" class = "btn btn-outline-light">Admin Login</button>
                <hr />
              </div>
            </div>-->
