<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$image = ninja_pfp($uid);
$cid = ninja_current_driver_company($uid);
$total = ninja_points($uid, $cid);

$iid = $_GET['ItemID'];
$catid = $_GET['CatalogID'];
$price = ninja_item_price($iid, $catid);

?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
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
      <button class = "btn btn-secondary" onclick="window.location.href = '<?php echo 'cart_add_item.php?ItemID='.$iid.'&Price='.$price.'&CatalogID='.$catid; ?>';">Add to Cart</button>
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
