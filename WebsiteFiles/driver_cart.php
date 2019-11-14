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

<div id = "cartBody">
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
      			echo '<div class = "row" style =>';
        		echo '<div class = "col-md-3">';
        	  	echo '<img src = "Assets/DefaultPicture.jpg" />';
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
      		echo '<div class = "row" style =>
        		<div class = "col-md-3">
			<p style = "top: 50%;">No Items in Cart</p>
		</div>
		</div>';
	}

	?>
        <hr />
      </div>
    </div>

<div id = "cartSideBar" style = "background-image: linear-gradient(to bottom right, #E5E5E5, #FFFFFF);">
<br/><br/><br/><br/><br/><br/>
<h1 style = "text-align:center; color: black;">Options</h1><hr>
  <ul class = "navbar-nav" id = "buttonList">
    <li class = "nav-item">
      <button class = "btn btn-primary" onclick = "window.location.href = 'place_order.php';" >Checkout</button>
      </li><br />
      <li class = "nav-item">
        <button class = "btn btn-primary" onclick="window.location.href = 'logon.php';" >Keep Shopping</button>
        </li>
    </ul>
</div>

</body>
