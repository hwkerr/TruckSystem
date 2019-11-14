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
		$palert = $_POST['PAlert'] == 'on';
		$oalert = $_POST['OAlert'] == 'on';
		$calert = $_POST['CAlert'] == 'on';
		ninja_address_update($uid, $street, $street2, $city, $state, $zip);
		ninja_update_phone($uid, $phone);
		ninja_update_alerts($uid, $palert, $oalert, $calert);
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
		$palert = ninja_point_alert($uid);
		$oalert = ninja_order_alert($uid);
		$calert = ninja_change_alert($uid);
	}
}

?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body style = "height: 100%;">
<?php include "driver_header.php";?>

<div class = "jumbotron">
	<h1>Edit Profile</h1>
</div>
<br><br>
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
	<img width = "200px" src = "Assets/ProfilePicture.jpg"></img>
	<hr>
	<br>
        <form action="upload_pfp.php" method="post" enctype="multipart/form-data">
          Select Image File to Upload:
          <input type="file" name="file">
          <input type="submit" name="submit" value="Upload">
        </form>
        <form action = "url_pfp.php" method = "post">
          <input type = "text" name="text">
          <input type = "submit" value="Upload URL">
        </form>

          <form style = "margin: 0 auto;" method = "post">
<?php
		if ($_SESSION['UserType'] === "Driver")
		{
			$pchecked = "";
			$ochecked = "";
			$cchecked = "";
			if ($palert)
				$pchecked = "checked";
			if ($oalert)
				$ochecked = "checked";
			if ($calert)
				$cchecked = "checked";
			echo '<div class = "form-group form-check">';
			echo '<input type = "checkbox" class = "form-check-input" id = "PAlert" name = "PAlert" '.$pchecked.'>';
			echo '<label class = "form-check-label" for = "PAlert">Receive Alerts for Point Additions</label>';
			echo '</div>';
			echo '<div class = "form-group form-check">';
			echo '<input type = "checkbox" class = "form-check-input" id = "OAlert" name = "OAlert" '.$ochecked.'>';
			echo '<label class = "form-check-label" for = "PAlert">Receive Alerts for Order Submissions</label>';
			echo '</div>';
			echo '<div class = "form-group form-check">';
			echo '<input type = "checkbox" class = "form-check-input" id = "CAlert" name = "CAlert" '.$cchecked.'>';
			echo '<label class = "form-check-label" for = "PAlert">Receive Alerts for Order Changes</label>';
			echo '</div>';
		}
?>
	</div>
	<div class = "col-md-6">
	<div class = "form-row"><br><br>
            <div class = "form-group col-md-6" style = "margin-center: auto;">
	      <label for = "InputFName">First Name</label>
              <input type= "text" name = "FName" class = "form-control" id = "InputFName" value = <?php echo '"'.$fname.'"' ?> placeholder="First Name"/>
            </div>
            <div class = "form-group col-md-6">
	      <label for = "InputLName">Last Name</label>
              <input type = "text" name = "LName" class = "form-control" id = "InputLName" value = <?php echo '"'.$lname.'"' ?> placeholder="Last Name"/>
            </div>
	</div>
		<?php
		if ($_SESSION['UserType'] === "Driver")
		{
			echo '<div class = "form-group">';
			echo '<label for = "InputPhone">Phone Number</label>';
			echo '<input type = "text" name = "Phone" class = "form-control" id = "InputPhone" value = "'.$phone.'" placeholder = "Phone Number (10-digit, no spaces/dashes/etc)"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<label for = "InputStreet">Street Address</label>';
			echo '<input type = "text" name = "Street" class = "form-control" id = "InputStreet" value = "'.$street.'" placeholder = "Street Address"/>';
			echo '</div>';
			echo '<div class = "form-group">';
			echo '<label for = "InputStreet2">Street Address 2</label>';
			echo '<input type = "text" name = "Street2" class = "form-control" id = "InputStreet2" value = "'.$street2.'" placeholder = "Street Address Line 2 (optional)"/>';
			echo '</div>';
			echo '<div class = "form-row">';
			echo '<div class = "form-group col-md-6">';
			echo '<label for = "InputCity">City</label>';
			echo '<input type = "text" name = "City" class = "form-control" id = "InputCity" value = "'.$city.'" placeholder = "City"/>';
			echo '</div>';
			echo '<div class = "form-group col-md-4">';
			echo '<label for = "InputState">State</label>';
			echo '<input type = "text" name = "State" class = "form-control" id = "InputState" value = "'.$state.'" placeholder = "State (2 letters)"/>';
			echo '</div>';
			echo '<div class = "form-group col-md-2">';
			echo '<label for = "InputZip">Zip</label>';
			echo '<input type = "text" name = "Zip" class = "form-control" id = "InputZip" value = "'.$zip.'" placeholder = "Zip Code (5-digit)"/>';
			echo '</div>';
			echo '</div>';
		}
		?>
		<br>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-primary btn-block">Save and Return to Profile</button>
            </div>
	    </div>
          </form>

        </div>
      </div>

      <br />
