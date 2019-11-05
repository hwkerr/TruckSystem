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
				echo '<div class = "col"><div class="card" style="width: 18rem;"><img class="card-img-top" src="data:image/png;base64,'. base64_encode($CustomImg) . '"><div class="card-body"><h5 class="card-title">'. $Name .'</h5><p class="card-text">Points: ' . $PointPrice .'</p></div></div></div>';
		}
	}
 ?>

<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
  <title>What the Truck</title>
<?php include "driver_header.php"; ?>
<br />
  <div class = "row">
    <div class = "col-md-2">
      <!--<div class = "card">
        <div class = "card-body">
            <button class = "btn btn-light btn-block" onclick = "sort_orders(1)">Alphabetical Sort(A-Z)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(2)">Alphabetical Sort(Z-A)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(3)">Price Sort(Low-High)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(4)">Price Sort(High-Low)</button>
          </div>
        </div>-->
      </div>
    <div class = "col-md-9">
      <div class = "Catalogue">
            <br />
						<form class="form-inline">
							<select class="custom-select my-1 mr-sm-2 " style = "width: 20%; float:right;" id="inlineFormCustomSelectPref">
								<option value = "1">Alphabetical Sort(A-Z)</option>
								<option value = "2">Alphabetical Sort(Z-A)</option>
								<option value = "3">Price Sort(Low-High)</option>
								<option value = "4">Price Sort(High-Low</option>
							</select>
							<button class = "btn btn-primary">Sort</button>
						</form>

              <div class = "col">
              <div class="card" style="width: 18rem;">
                  <img class="card-img-top" src="Assets/DefaultPicture.jpg">
                    <div class="card-body">
                      <a href = "driver_item_view.php"><h5 class="card-title">Sample Item</h5></a>
                      <p class="card-text">Points: 100</p>
                    </div>
                  </div>
                  </div>
              <br />
              <nav style = "margin: 0 auto; text-align: center;">
                <ul class = "pagination">
                  <li class = "page-item">
                    <a class = "page-link" href ="#"><</a>
                  </li>
                  <li class = "page-item">
                    <a class = "page-link" href = "#">1</a>
                  </li>
                  <li class = "page-item">
                    <a class = "page-link" href = "#">2</a>
                  </li>
                  <li class = "page-item">
                    <a class = "page-link" href = "#">3</a>
                  </li>
                  <li class = "page-item">
                    <a class = "page-link" href = "#">></a>
                  </li>
                  </ul>
                </nav>
          </div>
          </div>
        </div>
      </div>
    </div>
</body>

