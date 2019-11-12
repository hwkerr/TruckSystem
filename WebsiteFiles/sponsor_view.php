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
	$cname = ninja_company_name($cid);
	$cimage = ninja_company_pic($cid);
	$cinfo = ninja_company_sponsor_info($cid);
?>
<!DOCTYPE html>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
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
              View Catalogues
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showDriver()"
            class = "btn btn-outline-light ">
              Manage Drivers
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showApplication()"
            class = "btn btn-outline-light ">
              Applications
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showCompany()"
            class = "btn btn-outline-light ">
             View Company
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
            <a class = "nav-link" href = "contact_admin.php">Contact Us</a>
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
	    <th>View Catalog</th>
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
	 <td><a href = "catalog_view.php">View Catalog</a>
        </tr>
      </table>
    </div>
    <div id = "DriverContent" style = "display: block;">
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
	    <th>Add</th>
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
			echo '<td>';
			echo '<form action = "add_points.php">';
			echo '<input type = "text" name = "PointAdd">';
			echo '<input type = "hidden" name = "DID" value = "'.$row['UserID'].'">';
			echo '<input type = "submit" value = "Add Points">';
			echo '</form>';
			echo '</td>';
			echo '<td><a href="sponsor_driver_profile.php?DID='.$row['UserID'].'">View Profile</a></td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
        </tbody>
      </table>
    </div>
    </div>
    <div id = "ApplicationContent" style = "display: none;">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>Driver Applications</h1>
      </div>
      <div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Accept</th>
            <th>Reject</th>
          </tr>
        </thead>
	  <?php
		$entry = 1;
		$res = ninja_company_driver_applications($cid);
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td><a href="sponsor_approve_driver.php?DID='.$row['UserID'].'">Accept</a></td>';
			echo '<td><a href="sponsor_reject_driver.php?DID='.$row['UserID'].'">Reject</a></td>';
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
        <h1><?php echo $cname; ?></h1>
      </div>
        <div class = "container">
          <div class = "row">
            <div class = "col-sm-2"></div>
            <div class = "col-md-6">
              <div class = "card-body">
		<?php
			echo $cinfo;
		?>
              </div>
            </div>
            <div class = "col-md-3">
              <img src = <?php echo '"data:image/png;base64,'.base64_encode($cimage).'"';?> class = "img-rounded "></img>
            </div>
          </div>
          <div class = "row justify-content-center">
            <div class = "col-sm-3">
              <button class = "btn btn-primary" onclick = "window.location.href = 'edit_company.php';">Edit Information</button>
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
  var application = document.getElementById("ApplicationContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "block";
  driver.style.display = "none";
  application.style.display = "none";
  company.style.display = "none";
}
function showDriver(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var application = document.getElementById("ApplicationContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "block";
  application.style.display = "none";
  company.style.display = "none";
}
function showApplication(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var application = document.getElementById("ApplicationContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "none";
  application.style.display = "block";
  company.style.display = "none";
}
function showCompany(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var application = document.getElementById("ApplicationContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "none";
  application.style.display = "none";
  company.style.display = "block";
}
</script>
