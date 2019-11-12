<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] == 'Driver')
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
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
<body style = "height: 100%;background-image: linear-gradient(to bottom right, #071461, #0B358E);">
 <br /><br /><br />
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
    <div class = "col-lg-6" style = "text-align: center; color: white;">
      <h1>
        <br />Edit Company Profile<br/><hr/>
      </h1>
    </br>
    </div>
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6">
        <form action="upload_cimg.php" method="post" enctype="multipart/form-data" style = "color:white;">
          Select Image File to Upload:
          <input type="file" name="file">
          <input type="submit" name="submit" value="Upload">
        </form>
        <form action = "url_cimg.php" method = "post">
          <input type = "text" name="text">
          <input type = "submit" value="Upload URL">
        </form>
          <form style = "margin: 0 auto;" method = "post">
            <div class = "form-group" style = "margin-center: auto;">
              <input type= "text" name = "CName" class = "form-control" id = "InputCName" value = <?php echo '"'.$cname.'"'; ?> placeholder="Company Name"/>
            </div>
            <div class = "form-group">
              <input type = "text" name = "SponsorInfo" class = "form-control" id = "InputSponsorInfo" value = <?php echo '"'.$cinfo.'"'; ?> placeholder="Company Information"/>
            </div>
            <div class = "form-group">
              <input type = "text" name = "DriverAd" class = "form-control" id = "InputDriverAd" value = <?php echo '"'.$cdrad.'"'; ?> placeholder="Company Description for Drivers"/>
            </div>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-outline-light btn-block">Save and Return to Home</button>
            </div>
          </form>
        </div>
      </div>

      <br />
