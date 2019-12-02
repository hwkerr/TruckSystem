<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: DesktopSite.php");
	exit;
}

$uid = $_SESSION['UserID'];
$image = ninja_pfp($uid);
$cid = ninja_current_driver_company($uid);
$total = ninja_points($uid, $cid);

$iid = $_GET['ItemID'];
$catid = $_GET['CatalogID'];
$price = ninja_item_price($iid, $catid);
$name = ninja_item_name($iid, $catid);
$desc = ninja_item_description($iid, $catid);
$iimg = ninja_item_image($iid, $catid);

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
      <h1><?php echo $name; ?></h1><br />
    </div>
  </div>
  <div class = "row">
    <div class = "col-md-5" style = "text-align:center; border-right: 1px grey solid;">
      <img width = "350px" src = "data:image/png;base64,<?php echo base64_encode($iimg); ?>"/>
    </div>
    <div class = "col-lg-3 justify-content-center" style = "margin: auto;">
      <ul class = "list-group">
        <li class = "list-group-item">
          <p>
            Price: <?php echo $price; ?> points
          </p>
        </li>
	<li class = "list-group-item">
	<p>	
		Description: <?php echo $desc; ?>
	</p>
	</li>
      </ul><br />
      <button class = "btn btn-primary" onclick = "window.location.href = 'driver_view.php';">Back to Home</button>
      <button class = "btn btn-secondary" onclick="window.location.href = '<?php echo 'cart_add_item.php?ItemID='.$iid.'&Price='.$price.'&CatalogID='.$catid; ?>';">Add to Cart</button>
    </div>
  </div><br /><br />
  <div class = "row">
    <div class = "col-lg-9">
      <p>
      </p>
    </div>
  </div>
  <div class = "row">
    <div class = "col-lg-6">
    </div>
  </div>
</div>

</body>
