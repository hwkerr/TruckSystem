<?php
	include "../inc/dbinfo.inc";
	include "db_ninja.php";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];

	$cid = ninja_current_driver_company($uid);

	function sort_orders($type){
		//Creates the prepared statement
		if($type == '1')
	  		$statement = $db->prepare("SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY Name ASC");
		else if($type == '2')
			$statement = $db->prepare("SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY Name DESC");
		else if($type == '3')
			$statement = $db->prepare("SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY PointPrice ASC");
		else
				$statement = $db->prepare("SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY PointPrice DESC");

		//Pulls data from prepared statement
		$statement->execute();
		$res = $statement->get_result();
		$res->data_seek(0);
		while($row = $res->fetch_assoc())
		{
				$Name = $row['Name'];
				$PointPrice = $row['PointPrice'];
				$CustomImg = $row['CustomImg'];
				echo '<div class = "col">
					<div class="card" style="width: 18rem;">
						<img class="card-img-top" src="data:image/png;base64,'. base64_encode($CustomImg) . '">
							<div class="card-body">
								<h5 class="card-title">'. $Name .'</h5>
								<p class="card-text">Points: ' . $PointPrice .'</p>
							</div>
						</div>
				</div>';
		}
	}
 ?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>
<br />
      <div class = "container-fluid" id = "Catalog">
            <br />

		<div id = "sortSelect">
			<form class="form-inline">
				<select class="custom-select my-1 mr-sm-2 " style = "width: 20%; float:right;" id="inlineFormCustomSelectPref">
					<option value = "1">Alphabetical Sort(A-Z)</option>
					<option value = "2">Alphabetical Sort(Z-A)</option>
					<option value = "3">Price Sort(Low-High)</option>
					<option value = "4">Price Sort(High-Low</option>
				</select>
				<button class = "btn btn-primary">Sort</button>
			</form>
		</div>

	<?php
		$items = ninja_browse_catalog_items($cid);
		$rank = 0;
		while ($row = $items->fetch_assoc())
		{
			$name = $row['Name'];
			$iid = $row['ItemID'];
			$catid = $row['CatalogID'];
			$iimg = $row['Image'];
			if ($rank % 4 == 0)
              		echo '<div class = "row">';
			echo '<div class = "col-sm-4">';
              		echo 	'<div class="card" style="width: 18rem;">';
              		echo     '<img class="card-img-top" src="data:image/png;base64,'.base64_encode($iimg).'">';
              		echo       '<div class="card-body">';
              		echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
              		echo         '<p class="card-text">Points: 100</p>';
              		echo       '</div>';
              		echo     '</div>';
			echo   '</div>';
			if ($rank+1 % 4 == 0)
              			echo   '</div>';

			$rank++;
		}
		if ($rank % 4 != 0)
			echo '</div>';
	?>
              <br />
          </div>
</body>

