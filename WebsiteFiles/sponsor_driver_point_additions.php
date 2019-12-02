
<?php

include "db_ninja.php";

session_start();

        if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Sponsor')
        {
                header("location: DesktopSite.php");
                exit;
        }
$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);
$cid = ninja_sponsor_company_id($uid);
$did = $_GET['DriverID'];

?>

<html>
<?php include "htmlhead.php"?>
<body>
<?php include "sponsor_header.php"?>
<div class = "jumbotron">
<h1>Order History</h1>
</div>
<div class = "container">

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

