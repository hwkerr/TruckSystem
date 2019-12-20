<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: DesktopSite.php");
	exit;
}
$did = $_GET['DID'];
$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);

if ($_SERVER["REQUEST_METHOD"] == "POST")  // write changes
{
	$did = $_POST['DID'];
	$dfname = $_POST['FName'];
	$dlname = $_POST['LName'];
	ninja_update_name($did, $dfname, $dlname);
	$phone = $_POST['Phone'];
	$street = $_POST['Street'];
	$street2 = $_POST['Street2'];
	$city = $_POST['City'];
	$state = $_POST['State'];
	$zip = $_POST['Zip'];
	$palert = $_POST['PAlert'] == 'on';
	$oalert = $_POST['OAlert'] == 'on';
	$calert = $_POST['CAlert'] == 'on';
	ninja_address_update($did, $street, $street2, $city, $state, $zip);
	ninja_update_phone($did, $phone);
	ninja_update_alerts($did, $palert, $oalert, $calert);
	header("location: admin_view.php");  // redirect
}
else  // populate data
{
	$dfname = ninja_fname($did);
	$dlname = ninja_lname($did);
	$dpfp = ninja_pfp($did);
	$phone = ninja_phone($did);  // no dashes
	$street = ninja_address_street($did);
	$street2 = ninja_address_street2($did);
	$city = ninja_address_city($did);
	$state = ninja_address_state($did);
	$zip = ninja_address_zip($did);
	$palert = ninja_point_alert($did);
	$oalert = ninja_order_alert($did);
	$calert = ninja_change_alert($did);
}

?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body style = "height: 100%;">

<?php
	include "admin_header.php";
?>
<div class = "jumbotron">
	<h1>Edit Profile</h1>
</div>
<br><br>
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6" style = "border-right: 1px gray solid;">
	<img width = "200px" src = "<?php echo 'data:image/png;base64,'.base64_encode($dpfp); ?>"></img>
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
?>
	</div>
	<div class = "col-md-6" style = "margin:auto;">
	<div class = "form-row"><br><br>
            <div class = "form-group col-md-6" style = "margin-center: auto;">
	      <label for = "InputFName">First Name</label>
              <input type= "text" name = "FName" class = "form-control" id = "InputFName" value = <?php echo '"'.$dfname.'"' ?> placeholder="First Name"/>
            </div>
            <div class = "form-group col-md-6">
	      <label for = "InputLName">Last Name</label>
              <input type = "text" name = "LName" class = "form-control" id = "InputLName" value = <?php echo '"'.$dlname.'"' ?> placeholder="Last Name"/>
            </div>
	</div>
		<?php
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
			echo '<div class = "form-group col-md-2">';
			echo '<input type = "hidden" name = "DID" class = "form-control" id = "InputDID" value = "'.$did.'"/>';
			echo '</div>';
			echo '</div>';
		?>
		<br>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-primary">Save and Return</button>
            </div>
	    </div>
          </form>

        </div>
      </div>

      <br />
