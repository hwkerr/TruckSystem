<?php
	include "db_ninja.php";
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
	{
		header("location: DesktopSite.php");
		exit;
	}

	$uid = $_SESSION['UserID'];
?>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>
<div class = "jumbotron" style = "margin-bottom: 0rem;">
    <h1>View Sponsors</h1>
</div>
<table class = "table table-hover">
  <thead>
    <tr>
        <th>#</th>
        <th>Icon</th>
        <th>Company Name</th>
        <th>Number of Drivers</th>
        <th>Application</th>
    </tr>

<?php
	$comps = ninja_companies();
	$num = 1;
	while ($row = $comps->fetch_assoc())
	{
		$cid = $row['CompanyID'];
		$cname = $row['Name'];
		$image = ninja_company_pic($cid);
		$drivers = ninja_company_driver_count($cid);
		$status = ninja_driver_company_status($uid, $cid);
		echo '<tr>';
		echo '<td class = "center">';
		echo $num;
		echo '</td>';
		echo '<td>';
		echo '<img width = "40px" height = "40px" src = "data:image/png;base64,'.base64_encode($image).'"/>';
		echo '</td>';
		echo '<td>';
		echo $cname;
		echo '</td>';
		echo '<td>';
		echo $drivers;
		echo '</td>';
		echo '<td>';
		if ($status == -1)
			echo '<a href="apply_to_company.php?CID='.$cid.'">Apply</a>';
		else if ($status == 0)
			echo 'Application in process!';
		else if ($status == 1)
			echo 'You have joined this company!';
		echo '</td>';
		$num++;
	}
?>

    </thead>
  </table>

</body>
