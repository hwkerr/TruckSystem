<?php
	include "db_ninja.php";
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];
?>
<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel = "stylesheet" href = "style.css">
</head>
<body>
  <title>What the Truck!</title>
<?php include "driver_header.php"; ?>
<div class = "jumbotron" style = "margin-bottom: 0rem;">
    <h1>View Sonsors</h1>
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
