<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] == 'Driver')
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);
$cid = ninja_sponsor_company_id($uid);

if ($_SERVER["REQUEST_METHOD"] == "POST")  // write changes
{
	$cname = $_POST['CName'];
	$cinfo = $_POST['SponsorInfo'];
	$cdrad = $_POST['DriverAd'];
	ninja_update_company($cid, $cname, $cinfo, $cdrad);
	header("location: logon.php");  // redirect
}
else  // populate data
{
	$cname = ninja_company_name($cid);
	$cinfo = ninja_company_sponsor_info($cid);
	$cdrad = ninja_company_driver_ad($cid);
}

?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body style = "height: 100%;">
<?php include "sponsor_header.php";?>
<div class = "jumbotron">
	<h2>Edit Company Profile</h2>
</div>
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    </br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
	<div class = "col-md-6">
        <div class = "card">
        <div class = "card-header"><h4>Company Description</h4></div>
        <div class = "card-body">
          <form style = "margin: 0 auto;" method = "post">
            <div class = "form-group" style = "margin-center: auto;">
              <label for = "InputCName">Company Name</label>
              <input type= "text" name = "CName" class = "form-control" id = "InputCName" value = <?php echo '"'.$cname.'"'; ?> placeholder="Company Name"/>
            </div>
            <div class = "form-group">
              <label for = "InputSponsorInfo">Sponsor Info</label>
              <input type = "text" name = "SponsorInfo" class = "form-control" id = "InputSponsorInfo" value = <?php echo '"'.$cinfo.'"'; ?> placeholder="Company Information"/>
            </div>
            <div class = "form-group">
              <label for = "InputDriverAd">Company Description for Drivers</label>
              <input type = "text" name = "DriverAd" class = "form-control" id = "InputDriverAd" value = <?php echo '"'.$cdrad.'"'; ?> placeholder="Company Description for Drivers"/>
            </div>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-primary">Save and Return to Home</button>
            </div>
          </form>
        </div>
        </div>
        </div>

      <div class = "col-md-6">
	<div class = "card">
	<div class = "card-header"><h4>Company Image</h4></div>
	<div class = "card-body">
        <form action="upload_cimg.php" method="post" enctype="multipart/form-data">
          Select Image File to Upload:
	<div class = "form-row">
		<div class = "col-md-7">
	          <input class = "form-control-file" type="file" name="file">
		</div>
		<div class = "col-md-4">
	          <input class = "btn btn-primary" type="submit" name="submit" value="Upload">
		</div>
        </form>
        <form action = "url_cimg.php" method = "post">
	<div class = "form-row">
		<div class = "col-md-7">
        	  <input class = "form-control" placeholder = "Image URL" type = "text" name="text">
		</div>
		<div class = "col-md-4">
	          <input class = "btn btn-primary" type = "submit" value="Upload URL">
		</div>
	</div>
        </form>
	</div>
	</div>
	</div>
	</div>
      </div>

      <br />
