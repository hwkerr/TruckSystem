<?php include "db_ninja.php"; ?>
<html>
<body>
<?php
	session_start();

	if (isset($_SESSION['Logged']) && $_SESSION['Logged'] === true)
	{
		if ($_SESSION['UserType'] === "Driver")
		{
			header("location: driver_view.php");
		}
		else if ($_SESSION['UserType'] === "Sponsor")
		{
			header("location: sponsor_view.php");
		}
		else if ($_SESSION['UserType'] === "Admin")
		{
			header("location: admin_view.php");
		}
		exit;
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$email = htmlspecialchars($_POST['ID']);
		$pword = htmlspecialchars($_POST['Password']);
		$status = ninja_login($email, $pword);
		if ($status == 0)
		{
			header("location: change_password.php");
		}
		else if ($status == 1)
		{
			header("location: driver_view.php");
		}
		else if ($status == 2)
		{
			header("location: sponsor_view.php");
		}
		else if ($status == 3)
		{
			header("location: admin_view.php");
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
              <a href = "forgot_password.php" class = "form-text" style = "font-size: 12px; color: white;">Forgot Password?</a>
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
