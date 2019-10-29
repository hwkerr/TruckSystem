<?php
	include "db_ninja.php"; 
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Sponsor')
	{
		header("location: logon.php");
		exit;
	}
	
	$uid = $_SESSION['UserID'];
	$cid = ninja_sponsor_company_id($uid);
	$fullname = ninja_name($uid);
	$pfp = ninja_pfp($uid);
?>
<!DOCTYPE html>
<html style = "height: 100%;">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel = "stylesheet" href = "style.css">
</head>
<body style = "height: 100%;">
  <title>What the Truck!</title>
<div id = "homeBody">
    <!--SideNav-->
    <nav class = "justify-content-center" id = "sideNav">
      <div id = "AccountProfile">
        <button type = "button" style = "border-radius: 0rem; color:white;"class = "btn btn-link btn-block" onclick = "location.href = 'view_profile.php'">
          <div class = "ProfileName" >
          <span id = "Accountpicture"><img width = "60px" src =<?php echo '"data:image/png;base64,'.base64_encode($pfp).'"' ?> /></span><br />
            <p id = "AccountText">
              <?php echo $fullname; ?><br />
            </p>
          </div>
        </button>
        <hr style = "border-top: 1px solid white;"/>
      </div>
        <ul class = "navbar-nav" id = "buttonList">
          <li class = "nav-item">
            <button type = "button" onclick = "showCatalogue()"
            class = "btn btn-outline-light">
              Catalogues
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showDriver()"
            class = "btn btn-outline-light ">
              Drivers
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showCompany()"
            class = "btn btn-outline-light ">
              Company Info
            </button>
          </li>
        </ul>
    </nav>

    <!--Main Body-->
  <div id = "mainContent">
    <nav class = "navbar navbar-expand-md bg-light">
      <form class = "form-inline mt-2 mt-md-0" id = "searchBar" style = " width:auto;">
        <input class = "form-control" type = "text" placeholder="Search" />
        <button class = "btn btn-primary">Search</button>
      </form>
      <div class = "d-flex justify-content-end" style = "float:right;">
        <ul class = "navbar-nav mr-auto">
          <li class = "nav-item">
            <a class = "nav-link">Contact Us</a>
          </li>
          <li class = "nav-item" >
            <a class = "nav-link" href = "logout.php">Log Off</a>
          </li>
        </ul>
      </div>

    </nav>
    <div class = "CatalogContent" id = "CatalogInfo" style = "display: none;">
      <div class = "jumbotron" style = "margin-bottom: 0px;">
        <h1>Catalog info</h1>
      </div>
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Catalog Name</th>
            <th>Number of Items</th>
            <th>Items</th>
          </tr>
        </thead>
        <tr>
          <th>
            1
          </th>
          <td>
            Sample Catalog
          </td>
          <td>
            10
          </td>
          <td>
            <a href="#">View Items</a>
          </td>
        </tr>
      </table>
    </div>
    <div id = "DriverContent">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>Drivers</h1>
      </div>
      <div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Points</th>
            <th>Profile</th>
          </tr>
        </thead>
	  <?php
		$entry = 1;
		$res = ninja_company_driver_list($cid);
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td>'.ninja_points($row['UserID'], $cid).'</td>';
			echo '<td>Link</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
        </tbody>
      </table>
    </div>
    </div>
    <div class = "CompanyContent" id = "CompanyInfo" style = "display: none;">
      <div class = "jumbotron">
        <h1>CompanyName</h1>
      </div>
        <div class = "container">
          <div class = "row">
            <div class = "col-sm-2"></div>
            <div class = "col-md-6">
              <div class = "card-body">
                Section where company can write information about themselves. This can include what
                kind of products they have, what there point earning requirements are, contact info, etc.
              </div>
            </div>
            <div class = "col-md-3">
              <image src = "Assets/Download.png" class = "img-rounded "></image>
            </div>
          </div>
          <div class = "row justify-content-center">
            <div class = "col-sm-3">
              <button class = "btn btn-primary">Edit Information</button>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<br />
</body>

<script>
function showCatalogue(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "block";
  driver.style.display = "none";
  company.style.display = "none";
}
function showDriver(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "block";
  company.style.display = "none";
}
function showCompany(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "none";
  company.style.display = "block";
}
</script>
