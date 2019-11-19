
<?php

include "db_ninja.php";

session_start();

        if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
        {
                header("location: logon.php");
                exit;
        }
$uid = $_SESSION['UserID'];
$cid = ninja_current_driver_company($uid);
$did = $uid;

?>

<html>
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>
<div class = "jumbotron">
<h1>Order History</h1>
</div>
<div class = "container">

<!--Order Card-->
<?php

$orders = ninja_orders($uid, $cid);

$prev = '';
$total = 0;
$first = true;
while ($row = $orders->fetch_assoc())
{
	$neworder = ($prev != $row['OrderID']);
	if ($neworder && !$first)
	{		
           echo '     <h5>Order Summary</h5>
		<hr/>
			<p>Order Price: '.$total.'
			<br/>
			</p>
        	</div>
		</div><br>
		';
		$total = 0;
	}
	if ($neworder)
	{
	echo '
		<div class = "card">
	        <div class = "card-header">
		<h3 style = "display: inline-block;">Order</h3>
                <p style = "display: inline-block;float:right;">'.$row['Timestamp'].'</p>
		</div>
		<div class = "card-body">';
	}	
        echo '     <!--Itemcard-->
                <div class = "card">
                        <div class = "card-body">
                                <p>'.$row['Name'].'</p>
				<img src = "data:image/png;base64,'.base64_encode($row['Image']).'" width = " 80px"/>
		<!--<p style = "display: inline-block; margin: auto auto;">Orderstate</p> -->
				<p style = "display: inline-block;float:right;">'.$row['Price'].' points</p>
                        </div>
                </div><br/>';
	$prev = $row['OrderID'];
	$first = false;
	$total += $row['Price'];
}

           echo '     <h5>Order Summary</h5>
		<hr/>
			<p>Order Price: '.$total.'
			<br/>
			</p>
        </div>
</div><br>
';

?>

<!--Make sure to add a <br> inbetween cards so they aren't mushed together-->
<!--Point Addition Card-->
<?php

$gains = ninja_point_gains($did, $cid);
$num = 0;
while ($row = $gains->fetch_assoc())
{
	$num = $num + 1;
	$time = $row['Timestamp'];
	$amount = $row['Amount'];
	echo '<br/>';
	echo '<div class = "card">';
	echo '<div class = "card-header">';
	echo '<h4 style = "display: inline-block;">';
	echo 'Point Addition - '.$time;
	echo '</h4>';
	echo '</div>';
	echo '<div class = "card-body">';
	echo '<h4 style = "color: green;">';
	echo '+ '.$amount.' Points';
	echo '</h4>';
	echo '</div>';
	echo '</div>';
}

?>
</div>
</body>
</html>

