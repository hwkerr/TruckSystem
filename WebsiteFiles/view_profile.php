<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
{
	header("location: logon.php");
	exit;
}
$uid = $_SESSION['UserID'];

$name = ninja_name($uid);
$pfp = ninja_pfp($uid);

if ($_SESSION['UserType'] === "Driver")
{
	$cid = ninja_current_driver_company($uid);
	$phone = ninja_phone_dashes($uid);
	$company = ninja_company_name($cid);
	$spent = ninja_point_subtractions($uid, $cid);
	$earned = ninja_point_additions($uid, $cid);
	$address = ninja_address_oneline($uid);
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
  <title><?php echo $name ?></title><br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br /><?php echo $name ?><br/><hr/>
      </h1>
    </br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
          <div class = "ProfileName" style = "color: white;">
          <span id = "Accountpicture"><img width = "60px" src =<?php echo '"data:image/png;base64,'.base64_encode($pfp).'"' ?> /></span><br />
            <p id = "AccountText">
              <?php echo ""; ?><br />
            </p>
		<?php
		if ($_SESSION['UserType'] === "Driver")
		{
			echo "<p id = 'PhoneNumber'>";
			echo "Phone: ".$phone;
			echo "</p>";
			echo "<p id = 'Address'>";
			echo "Address: ".$address;
			echo "</p>";
			echo "<p id = 'CurrentCompany'>";
			echo "Current Company: ".$company;
			echo "</p>";
			echo "<p id = 'SpentPoints'>";
			echo "Total Points Spent: ".$spent;
			echo "</p>";
			echo "<p id = 'EarnedPoints'>";
			echo "Total Points Earned: ".$earned;
			echo "</p>";
			echo "<p id = 'DriverSpace'>";
			echo "";
			echo "</p>";
		}
		?>
          </div>
          <form style = "margin: 0 auto;" action = "logon.php">
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-outline-light btn-block">Return to Home</button>
              <a href = "edit_profile.php" class = "form-text" style = "font-size: 12px; color: white;">Edit Information</a>
            </div>
          </form>
        </div>
      </div>

      <br />
