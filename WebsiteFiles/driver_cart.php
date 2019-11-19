<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}

$uid = $_SESSION['UserID'];
$fname = ninja_fname($uid);

?>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>

<div id = "cartBody" style = "width: 100%;">
  <div class = "jumbotron">
    <h1><?php echo $fname; ?>'s Cart</h1>
    </div>
    <div class = "container-fluid">
	<?php

	if (isset($_SESSION['Cart']) && count($_SESSION['Cart']) > 0)
	{	
		$items = $_SESSION['Cart'];
		$element = 0;
		foreach ($items as $item)
		{
			$iid = $item['ItemID'];
			$catid = $item['CatalogID'];
			$price = $item['Price'];
			$iname = ninja_item_name($iid, $catid);
			$iimg = ninja_item_image($iid, $catid);
      			echo '<div class = "row">';
        		echo '<div class = "col-md-3">';
        	  	echo '<img width = "100px " src = "data:image/png;base64,'.base64_encode($iimg).'" />';
        	  	echo '</div>';
        	  	echo '<div class = "col-md-6" style = "vertical-align: middle;">';
        	    	echo '<p style = "top: 50%;">';
        	      	echo $iname;
        	    	echo '</p>';
        	    	echo '</div>';
        	    	echo '<div class = "col-md-3" style = "text-align: center;">';
        	      	echo '<p>';
        	        echo $price.' points';
        	      	echo '</p>';
        	      	echo '</div>';
        	    	echo '<div class = "col-md-3" style = "text-align: center;">';
        	      	echo '<p>';
			echo '<a href="cart_remove_item.php?Element='.$element.'">Remove</a>';
        	      	echo '</p>';
        	      	echo '</div>';
        		echo '</div>';

			$element++;
		}
	}
	else
	{
      		echo '<div class = "row">
        		<div class = "col-md-3">
			<p style = "top: 50%;">No Items in Cart</p>
		</div>
		</div>';
	}

	?>
        <hr />
      </div>
    </div>
<div class = "card" id = "cartMenu" style = "position: fixed; bottom: 20; left: 30%; width: 40%;">
	<div class = "card-header" style = "text-align: center;"><h5>Cart Status</h5></div>
	<div class = "card-body">
		<p style = "display: inline-block; float: left; vertical-align: center;">Order total: Price</p>
		<button class = "btn btn-primary" style = "display: inline-block; vertical-align: center; text-align: right; float: right;" onclick = "window.location.href = 'place_order.php';">Checkout</button>
		<button class = "btn btn-light" style = "display: flex; align-items: center; float: right;" onclick="window.location.href = 'logon.php';">Keep Shopping</button>
	</div>
</div>
</body>
