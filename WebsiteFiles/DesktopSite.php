<?php include "db_ninja.php";?>
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
<!DOCTYPE html>
<html style = "height: 100%;">
<?php include "htmlhead.php";?>
<body style = "background-color:transparent;">
  <title>What the Truck</title>
	<div class = "container-fluid">
  <nav class = "navbar navbar-expand-lg" style = "z-index:2000;">
<a class = "navbar-brand text-light" >What the Truck!</a>
  <ul class = "nav navbar-nav ml-auto">
	<li class = "nav-item">
	<!--	<a class = "nav-link text-light" data-toggle="modal" data-target = "#ApplicationModal">Apply to Join</a>-->
		<a class = "nav-link text-light" href = "application.php">Apply to Join</a>
	</li>
	<li class = "nav-item dropdown">
		<a class = "nav-link dropdown-toggle text-light" data-toggle = "dropdown">Log In</a>
                	<form method = "post" class = "dropdown-menu dropdown-menu-right p-4" style = "min-width: 30rem;">
				<div class = "form-group">
						<label for = "eMail">Email</label>
						<input type = "email" name = "ID"class = "form-control input-lg" id = "eMail" placeholder = "Email">
				</div>
				<div class = "form-group">
					<label for = "password">Password</label>
					<input type = "password" name = "Password" class = "form-control input-lg" id = "password" placeholder = "Password">
				</div>
				<div class = "row">
					<div class = "col-md-4">
						<button class = "btn btn-secondary"><a href = "forgot_password.php" class = "text-light">Forgot Password</a></button>
					</div>
					<div class = "col-md-4">
						<button type = "submit" class = "btn btn-primary btn-block" onclick = "location.href = 'logon.php'">Logon</button>
					</div>
				</div>
               		</form>
	</li>
  </ul>
	</nav>
</div>
<div class = "container-fluid" style = "z-index:1900;position:relative;">
  <div class = "row justify-content-center">
	<div class = "col-md-6" style = "position:fixed; vertical-align:middle; top:30%;">
  		<div id = "title" style = "vertical-align:middle; text-align:center; position:relative; top:30%;" class = "align-middle my-auto text-light">
			<h1 class = "display-1">What the Truck!</h1>
			<hr style = "border-top: 1px solid rgba(255,255,255,.5); width:50%;"/>
			<p class = "lead" style = "z-index:2000; font-size: 30px;">The Best Truckin Experience of Your Life!</p>
 		 </div> 
	</div>
  </div>
</div>
</div>
<div class = "modal-backdrop fade-show" style = "opacity: .3;"></div>
</body>

</html>



<div class = "modal fade" id = "ApplicationModal" style = "z-index:100000000000; position: relative;">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-header">
				<h4>Submit an Application</h4>
			</div>
			<div class = "modal-body">
				<form>
					<div class = "row">
						<div class = "col">
							<label for = "applicationFirstName">First Name</label>
							<input class = "form-control" placeholder = "First Name" id = "applicationFirstName" name = "FName" required>
						</div>
						<div class = "col">
							<label for = "applicationLastName">Last Name</label>
							<input class = "form-control" placeholder = "Last Name" id = "applicationLastName" name = "LName" required>
						</div>
					</div>
					<div class = "row">
						<div class = "col">
							<label for = "applicationEmail">Enter Email</label>
							<input class = "form-control" placeholder = "Email" id = "applicationEmail" name = "Email" required>
						</div>
						<div class = "col">
							<label for = "DriverorSponsor">Application Type</label>
							<select name = "DriverSponsor" class = "form-control" id = "DriverorSponsor">
								<option>Driver</option>
								<option>Sponsor</option>
							</select>
						</div>
					</div><br>
					<div class = "row">
						<div class = "col">
							<textarea name = "Info" class = "form-control" placeholder = "Tell us anything about yourself that we should know!"></textarea>
						</div>
					</div>
					<br><br>
					<div class = "row">
						<div class = "col" style = "text-align:center;">
							<button class = "btn btn-secondary" data-dismiss = "modal">Close</button>
							<button class = "btn btn-primary">Submit Application</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
html {
  background: url(Assets/Wallpaper.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

</style>
