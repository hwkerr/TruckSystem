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
<?php include "sponsor_header.php"?>
  <title>What the Truck!</title>
<nav class = "navbar navbar-expand-lg navbar-light bg-light">
	<ul class = "nav nav-pills" id = "tabs">
		<li class = "nav-item">
			<a class = "nav-link active" data-toggle = "pill" href = "#Company-Tab" aria-selected = "true">Dashboard</a>
		</li>
		<li class = "nav-item">
			<a class = "nav-link" data-toggle = "pill" href = "#Application-Tab" aria-selected = "false">View Applications</a>
		</li>
		<li class = "nav-item">
			<a class = "nav-link" data-toggle = "pill" href = "#Catalog-Tab" aria-selected = "false">View Catalogs</a>
		</li>
		<li class = "nav-item">
			<a class = "nav-link" data-toggle = "pill" href = "#Sponsor-Tab" aria-selected = "false">View Sponsors</a>
		</li>
		<li class = "nav-item">
			<a class = "nav-link" data-toggle = "pill" href = "#Driver-Tab" aria-selected = "false">View Drivers</a>
		</li>
</nav>
<div class = "tab-content">
<div class = "tab-pane fade-show" id = "Catalog-Tab" role = "tabpanel">
      <div class = "jumbotron" style = "margin-bottom: 0px;">
        <h1>Catalog info</h1>
      </div>
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Catalog Name</th>
            <th>Number of Items</th>
	    <th>Visible to Drivers?</th>
	    <th>View Catalog</th>
	    <th>Delete</th>
          </tr>
        </thead>
	<?php

	$catalogs = ninja_catalogs($cid);
	$num = 1;
	while ($row = $catalogs->fetch_assoc())
	{
		echo '<tr>';
		echo '<th>';
		echo $num;
		echo '</th>';
		echo '<td>';
		echo $row['Name'];
		echo '</td>';
		echo '<td>';
		echo ninja_catalog_item_count($row['CatalogID']);
		echo '</td>';
		echo '<td>';
		if (ninja_catalog_visible($row['CatalogID']))
			echo 'Visible';
		else
			echo 'Not Visible';
		echo '</td>';
		echo '<td>';
		echo '<a href = "catalog_view.php?CatalogID='.$row['CatalogID'].'">View Catalog</a>';
		echo '</td>';
		echo '<td>';
		echo '<a href = "delete_catalog.php?CID='.$row['CatalogID'].'">Delete Catalog</a>';
		echo '</td>';
		echo '</tr>';

		$num++;
	}

	?>
      </table>
	<div id = "newCatalog" style = "margin: auto;">
	<div class = "card" style = "max-width: 600px; margin-left: 20px;">
	<div class = "card-header">
		<h2 class = "lead">New Empty Catalog</h2>
	</div>
	<div class = "card-body">
        <form action = "create_catalog.php" method = "post">
	  <div class = "form-row">
		<div class = "col-md-8">
        	  	<input class = "form-control" type = "text" name="NewCatalogName" placeholder = "New Catalog Name" required>
	  		<input type = "hidden" name = "CID" value = "<?php echo $cid; ?>">
		</div>
		<div class = "col-md-3">
        	  	<button class = "btn btn-primary" type = "submit" value="Create New Catalog">Create New Catalog</button>
		</div>
	</div>
        </form>
	</div>
	</div>
	</div>
    </div>

    <div class = "tab-pane fade-show" id = "Driver-Tab" role = "tabpanel">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1><?php echo $cname;?> Drivers</h1>
      </div>
      <div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
	    <th>Profile</th>
            <th>Points</th>
	    <th>Add Points</th>
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
			echo '<td><a href="sponsor_driver_profile.php?DID='.$row['UserID'].'">View Profile</a></td>';
			echo '<td>'.ninja_points($row['UserID'], $cid).'</td>';
			echo '<td>';
			echo '<form action = "add_points.php">';
			echo '<div class = "form-row">';
			echo '<div class = "col-sm-6"><input class = "form-control" type = "text" name = "PointAdd" placeholder = "Add Points">';
			echo '<input type = "hidden" name = "DID" value = "'.$row['UserID'].'"></div>';
			echo '<div class = "col-sm-3"><input class = "btn btn-primary" type = "submit" value = "Add Points"></div>';
			echo '</div>';
			echo '</form>';
			echo '</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
        </tbody>
      </table>
    </div>
    </div>
    <div class = "tab-pane fade-show" id = "Application-Tab">
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

    <div class = "tab-pane fade-show active" id = "Company-Tab">
      <div class = "jumbotron">
        <h1><?php echo $cname;?> Dashboard</h1>
      </div>
        <div class = "container-fluid">
	  <div class = "row">
		<div class = "col-lg-4">
		   <div class = "card">
			<div class = "card-header">
			<p class = "lead">Create New Sponsor Account</p>
			</div>
			<div class = "card-body">

				<form action="create_sponsor.php" class = "needs-validation" method="ge" novalidate>
                                        <div class="row">
                                                <div class="col-lg-6">
                                                        <label for="fName">First Name</label>
                                                        <input type="text" class="form-control" id="FName" placeholder="First name" name="FName" required>
                                                        <div class = "invalid-feedback">Enter a valid first name</div>
                                                </div>
                                        </div>
                                        <div class = "row">
                                                <div class="col-lg-6">
                                                        <label for="lName">Last Name</label>
                                                        <input type="text" class="form-control" id="LName" placeholder="Last name" name="LName" required>
                                                        <div class = "invalid-feedback">Enter a valid last name</div>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-lg-6">
                                                        <label for="newEmail">Email</label>
                                                        <input type="email" class="form-control" id="Email" placeholder="Enter email" name="Email" required>
                                                        <div class = "invalid-feedback">Enter a valid email</div>
                                                </div>
                                        </div>
                                        <br>
                                        <button class="btn btn-primary" action="submit">Create Account</button>
        			</form>


			</div>
		   </div>
		</div>
		<div class = "col-lg-4">
		   <div class = "card">
			<div class = "card-header">
				<p class = "lead">Company Statistics</p>
			</div>
			<div class = "card-body">
			<ul class = "list-group-flush">
				<li class = "list-group-item">Total Points Held by Drivers: <?php echo number_format(ninja_company_points_held($cid)); ?></li>
				<li class = "list-group-item">Total Points Spent by Drivers: <?php echo number_format(ninja_company_points_spent($cid)); ?></li>
				<li class = "list-group-item">Total Points Earned by Drivers: <?php echo number_format(ninja_company_points_added($cid)); ?></li>
			</ul>
			</div>
		   </div>
	  </div>
         
            <div class = "col-lg-4">
	     <div class = "card">
		<div class = "card-header">
			<p class = "lead">Company Description</p>
			<p class = "subtext">How Your Company Appears to Drivers</p>
		</div>
              <div class = "card-body">
		<ul class = "list-group-flush">
			<li class = "list-group-item">Company Name: <?php echo $cname;?></li>
			<li class = "list-group-item">Company Description: <?php echo $cinfo;?></li>
			<li class = "list-group-item">Company Picture:</br> 
              <img width = "200px" src = <?php echo '"data:image/png;base64,'.base64_encode($cimage).'"';?> class = "img-rounded "></img></li></ul><br>
		<button class = "btn btn-primary" onclick = "window.location.href = 'edit_company.php';">Edit Information</button>
            </div>
          </div>
	</div>
	</div>
        </div>
    </div>


    <div class = "tab-pane fade-show" id = "Sponsor-Tab">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1><?php echo $cname; ?> Employees</h1>
      </div>
      <div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Total Points Added</th>
          </tr>
        </thead>
	  <?php
		$entry = 1;
		$res = ninja_company_sponsor_list($cid);
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td>'.ninja_sponsor_points_added($row['UserID'], $cid).'</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
        </tbody>
      </table>
    </div>
    </div>

  </div>
<br />
</body>
