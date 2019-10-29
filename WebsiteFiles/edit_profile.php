<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
{
	header("location: logon.php");
	exit;
}
$uid = $_SESSION['UserID'];

if ($_SERVER["REQUEST_METHOD"] == "POST")  // write changes
{
	$fname = $_POST['FName'];
	$lname = $_POST['LName'];
	ninja_update_name($uid, $fname, $lname);
	if ($_SESSION['UserType'] === "Driver")
	{
		$phone = $_POST['Phone'];
		$street = $_POST['Street'];
		$street2 = $_POST['Street2'];
		$city = $_POST['City'];
		$state = $_POST['State'];
		$zip = $_POST['Zip'];
		ninja_address_update($uid, $street, $street2, $city, $state, $zip);
		ninja_update_phone($uid, $phone);
	}
	header("location: view_profile.php");  // redirect
}
else  // populate data
{
	$fname = ninja_fname($uid);
	$lname = ninja_lname($uid);
	if ($_SESSION['UserType'] === "Driver")
	{
		$phone = ninja_phone($uid);  // no dashes
		$street = ninja_address_street($uid);
		$street2 = ninja_address_street2($uid);
		$city = ninja_address_city($uid);
		$state = ninja_address_state($uid);
		$zip = ninja_address_zip($uid);
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
  <title>Edit Profile</title><br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br />Edit Profile<br/><hr/>
      </h1>
    </br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
        <form action="upload_pfp.php" method="post" enctype="multipart/form-data" style = "color:white;">
          Select Image File to Upload:
          <input type="file" name="file">
          <input type="submit" name="submit" value="Upload">
        </form>
        <form action = "url_pfp.php" method = "post">
          <input type = "text" name="text">
          <input type = "submit" value="Upload URL">
        </form>
          <form style = "margin: 0 auto;" method = "post">
            <div class = "form-group" style = "margin-center: auto;">
              <input type= "text" name = "FName" class = "form-control" id = "InputFName" value = <?php echo $fname ?> placeholder="First Name"/>
            </div>
            <div class = "form-group">
              <input type = "text" name = "LName" class = "form-control" id = "InputLName" value = <?php echo $lname ?> placeholder="Last Name"/>
            </div>
		<?php
		if ($_SESSION['UserType'] === "Driver")
		{
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "Phone" class = "form-control" id = "InputPhone" value = "'.$phone.'" placeholder = "Phone Number (10-digit, no spaces/dashes/etc)"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "Street" class = "form-control" id = "InputStreet" value = "'.$street.'" placeholder = "Street Address"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "Street2" class = "form-control" id = "InputStreet2" value = "'.$street2.'" placeholder = "Street Address Line 2 (optional)"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "City" class = "form-control" id = "InputCity" value = "'.$city.'" placeholder = "City"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "State" class = "form-control" id = "InputState" value = "'.$state.'" placeholder = "State (2 letters)"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<input type = "text" name = "Zip" class = "form-control" id = "InputZip" value = "'.$zip.'" placeholder = "Zip Code (5-digit)"/>';
			echo '</div>';
		}
		?>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-outline-light btn-block">Save and Return to Profile</button>
            </div>
          </form>
        </div>
      </div>

      <br />
