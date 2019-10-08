<?php
	include "../inc/dbinfo.inc";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];
	$st = $db->query("SELECT Image FROM Account WHERE UserID = '$uid'");
	if ($row = $st->fetch_assoc())
	{
		$image = $row['Image'];
	}

	/*
	$total = 0;
	$driverID = $_SESSION['UserID'];
	$st = $db->prepare("SELECT SUM(Amount) AS Total FROM DriverPointAddition WHERE DriverID = ?");
	$st->bind_param("s", $_SESSION['UserID']);
	$st->execute();
	$res = $st->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		$total = $total + $row['Total'];
	}
	$st = $db->prepare("SELECT SUM(PointPrice) AS Total FROM OrderCatalogItem INNER JOIN Order ON Order.OrderID = OrderCatalogItem.OrderID WHERE Order.DriverID = ?");
	$st->bind_param("s", $_SESSION['UserID']);
	$st->execute();
	$res = $st->get_result();
	$res->data_seek(0);
	if ($row = $res->fetch_assoc())
	{
		$total = $total - $row['Total'];
	}*/
?>

<?php
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
  <title>What the Truck!</title>
<nav class = "navbar navbar-expand-lg navbar-default" style = "background-image:linear-gradient(to right, #071461, #0B358E); box-shadow: 8px 8px 8px 5px rgba(0, 0, 255, .1);" >
  <button type = "button" class = "btn btn-outline-light" onclick = "location.href" = "DesktopSite.html"><div class = "ProfileName" >
    <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($image).'"'?> /></span>
      <p style = "vertical-align: middle; display: inline-block; margin: auto;">
        <?php echo htmlspecialchars($_SESSION['Email']); ?><br />Points: <?php echo htmlspecialchars($total); ?>
      </p>
    </div></button>
  <div class = "navbar nav-right" id = "navbarNav">
  <ul class = "navbar-nav">
    <li class = "nav-item" style = "color: white;">
      <a class = "nav-link">Contact Us</a>
    </li>
    <li class = "nav-item" style = "color:white;">
      <a class = "nav-link" href = "DesktopSite.html">Log Off</a>
    </li>
    <form action = "logout.php">
      <input type = "submit" value = "Log Off">
    </form>
    <form action="upload_pfp.php" method="post" enctype="multipart/form-data">
      Select Image File to Upload:
      <input type="file" name="file">
      <input type="submit" name="submit" value="Upload">
    </form>
    <form action = "url_pfp.php" method = "post">
      <input type = "text" name="text">
      <input type = "submit" value="Upload URL">
    </form>
  </ul>
  </div>
</nav>
<br />
  <div class = "row">
    <div class = "col-md-2">
      <div class = "card">
        <div class = "card-body">
            <button class = "btn btn-light btn-block" onclick = "sort_orders(1)">Alphabetical Sort(A-Z)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(2)">Alphabetical Sort(Z-A)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(3)">Price Sort(Low-High)</button><br />
            <button class = "btn btn-light btn-block" onclick = "sort_orders(4)">Price Sort(High-Low)</button>
          </div>
        </div>
      </div>
    <div class = "col-md-9">
      <div class = "Catalogue" style = "background-color: gray;">
          <div class = "card">
            <div class = "card-body">
            <br />
            <div class = "row">
              <div class = "col">
              <div class="card" style="width: 18rem;">
                  <img class="card-img-top" src="Assets/DefaultPicture.jpg">
                    <div class="card-body">
                      <h5 class="card-title">Sample Item</h5>
                      <p class="card-text">Points: 100</p>
                    </div>
                  </div>
                  </div>
                  <div class = "col">
                  <div class="card" style="width: 18rem;">
                      <img class="card-img-top" src="Assets/DefaultPicture.jpg">
                        <div class="card-body">
                          <h5 class="card-title">Sample Item</h5>
                          <p class="card-text">Points: 100</p>
                        </div>
                      </div>
                      </div>
                      <div class = "col">
                      <div class="card" style="width: 18rem;">
                          <img class="card-img-top" src="Assets/DefaultPicture.jpg">
                            <div class="card-body">
                              <h5 class="card-title">Sample Item</h5>
                              <p class="card-text">Points: 100</p>
                            </div>
                          </div>
                          </div>
                          <div class = "col">
                          <div class="card" style="width: 18rem;">
                              <img class="card-img-top" src="Assets/DefaultPicture.jpg">
                                <div class="card-body">
                                  <h5 class="card-title">Sample Item</h5>
                                  <p class="card-text">Points: 100</p>
                                </div>
                              </div>
                              </div>
            </div><br />
              </div>
              <nav style = "margin: 0 auto;">
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


<!--SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY Name ASC -->
<!--SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY Name DESC -->
<!--SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY PointPrice ASC -->
<!--SELECT Name, PointPrice, CustomImg FROM CatalogCatalogItem ORDER BY PointPrice DESC -->
