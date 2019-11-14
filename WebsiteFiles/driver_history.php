
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
<div class = "card">
        <div class = "card-body">
		<h3 style = "display: inline-block;">Order</h3>
                <p style = "display: inline-block;float:right;">MM/DD/YYYY</p>
		<br/>
                <!--Itemcard-->
                <div class = "card">
                        <div class = "card-body">
                                <p>ItemName</p>
				<img src = "Assets/DefaultPicture.jpg" width = " 80px"/>
		<!--<p style = "display: inline-block; margin: auto auto;">Orderstate</p> -->
				<p style = "display: inline-block;float:right;">Price</p>
                        </div>
                </div><br/>
                <h5>Order Summary</h5>
		<hr/>
			<p>Total Balance: x
			<br/>
			Order Price: y<br/>
			New Balance: x - y
			</p>
        </div>
</div>

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
	echo '<div class = "card-body">';
	echo '<h4 style = "display: inline-block;">';
	echo 'Point Addition - '.$time;
	echo '</h4>';
	echo '<h4 style = "display: inline-block; color: green;float:right;">';
	echo '+ '.$amount.' Points';
	echo '</h4>';
	echo '</div>';
	echo '</div>';
}

?>
</div>
</body>
</html>

