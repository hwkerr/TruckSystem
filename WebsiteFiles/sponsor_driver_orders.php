
<?php

include "db_ninja.php";

session_start();

        if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Sponsor')
        {
                header("location: logon.php");
                exit;
        }
$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);
$cid = ninja_sponsor_company_id($uid);
$did = $_GET['DriverID'];

$cards = array();

?>

<html>
<?php include "htmlhead.php"?>
<body>
<?php include "sponsor_header.php";?>
<div class = "jumbotron">
<h1>Order History</h1>
</div>
<div class = "container">

<?php

$orders = ninja_orders($did, $cid);

$prev = '';
$total = 0;
$first = true;
$new = true;
$card = '';
$timestamp = '';
while ($row = $orders->fetch_assoc())
{
	$image64 = 'data:image/png;base64,'.(base64_encode($row['Image']));
	if ($first || $prev != $row['OrderID'])  // decide if this goes on a new order
	{
		$new = true;
	}
	
	if ($new && !$first)  // end the previous order
	{
           	$card = $card.'     <h5>Order Summary</h5>
		<hr/>
			<p>Order Price: '.$total.'
			<br/>
			</p>
        	</div>
		</div><br>';
		$cards[$timestamp] = $card;
	}
	
	if ($new)  // start a new order
	{
		$card = '<div class = "card">
	        <div class = "card-header">
		<h3 style = "display: inline-block;">Order</h3>
                <p style = "display: inline-block;float:right;">'.$row['Timestamp'].'</p>
		</div>
		<div class = "card-body">';
		$timestamp = $row['Timestamp'];
		$new = false;
		$total = 0;
	}
        $card = $card.'     <!--Itemcard-->
                <div class = "card">
                        <div class = "card-body">
                                <p>'.$row['Name'].'</p>
				<img src = "'.$image64.'" width = " 80px"/>
		<!--<p style = "display: inline-block; margin: auto auto;">Orderstate</p> -->
				<p style = "display: inline-block;float:right;">'.$row['Price'].' points</p>
                        </div>
                </div><br/>';
	$prev = $row['OrderID'];
	$first = false;
	$total += $row['Price'];
}
if (!$first)  // end the last order, if there was one
{
       	$card = $card.'     <h5>Order Summary</h5>
	<hr/>
		<p>Order Price: '.$total.'
		<br/>
		</p>
       	</div>
	</div><br>';
	$cards[$timestamp] = $card;
}

?>
<?php

krsort($cards);
foreach ($cards as $onecard)
	echo $onecard;

?>

</div>
</body>
</html>

