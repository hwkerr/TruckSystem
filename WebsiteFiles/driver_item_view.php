<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$image = ninja_pfp($uid);
$cid = ninja_current_driver_company($uid);
$total = ninja_points($uid, $cid);

?>

<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
  <title>What the Truck!</title>
<?php include "driver_header.php"; ?>
<br/>
<div class = "container">
  <br /><br />
  <div class = "row">
    <div class = "col-md-6">
      <h1>Item Name</h1><br />
    </div>
  </div>
  <div class = "row">
    <div class = "col-md-5" style = "text-align:center;">
      <img src = "Assets/DefaultPicture.jpg"/>
    </div>
    <div class = "col-lg-3 justify-content-center">
      <ul class = "list-group">
        <li class = "list-group-item">
          <p>
            Price: Price
          </p>
        </li>
        <li class = "list-group-item">
          <p>
            Available?: Maybe
          </p>
        </li>
      </ul><br />
      <button class = "btn btn-primary">Buy Now</button>
      <button class = "btn btn-secondary">Add to Cart</button>
    </div>
  </div><br /><br />
  <div class = "row">
    <div class = "col-lg-9">
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.  </p>
    </div>
  </div>
  <div class = "row">
    <div class = "col-lg-6">
      <button class = "btn btn-primary">Back to Home</button>
    </div>
  </div>
</div>

</body>
