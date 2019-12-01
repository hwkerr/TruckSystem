<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
{
	header("location: logon.php");
	exit;
}
$sid = $_GET['SID'];
$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);

if ($_SERVER["REQUEST_METHOD"] == "POST")  // write changes
{
	$did = $_POST['DID'];
	$dfname = $_POST['FName'];
	$dlname = $_POST['LName'];
	ninja_update_name($sid, $dfname, $dlname);
	header("location: admin_view.php");  // redirect
}
else  // populate data
{
	$dfname = ninja_fname($sid);
	$dlname = ninja_lname($sid);
	$dpfp = ninja_pfp($sid);
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
      <div class = "col-md-6">
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
	</div>
	<div class = "col-md-6">
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
		<br>
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-primary btn-block">Save and Return</button>
            </div>
	    </div>
          </form>

        </div>
      </div>

      <br />
