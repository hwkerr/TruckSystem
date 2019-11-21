
<?php
	include "db_ninja.php";
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];
	$fullname = ninja_name($uid);
	$pfp = ninja_pfp($uid);
?>

<!DOCTYPE html>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body style = "height: 100%;">
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
            <button type = "button" onclick = "showContent(1)"
            class = "btn btn-outline-light">
              Approve Applications
            </button>
            <br /><br />
          </li>
          <li class = "nav-item" id = "buttonList">
            <button type = "button" onclick = "showContent(2)"
            class = "btn btn-outline-light">Create/Mangage Account
          </button>
          <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showContent(3)"
            class = "btn btn-outline-light ">
              View Sponsors/Drivers
            </button>
            <br /><br />
          </li>
          <li class = "nav-item" id = "buttonList">
            <button type = "button" onclick = "showContent(6)"
            class = "btn btn-outline-light">
		View Companies
          </button>
          <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showContent(4)"
            class = "btn btn-outline-light ">
              Create Order/Item
            </button>
            <br /><br />
          </li>
          <li class = "nav-item" id = "buttonList">
            <button type = "button" onclick = "showContent(5)"
            class = "btn btn-outline-light">Analytics
          </button>
          <br /><br />
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
          <li class = "nav-item">
            <a class = "nav-link" href = "logout.php">Log Off</a>
          </li>
        </ul>
      </div>

    </nav>
    <div id = "Applications">
      <div class = "jumbotron" style = "margin-bottom: 0px;">
        <h1>Applications</h1>
      </div>
      <div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
	    			<th>Accept</th>
            <th>Type</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Info</th>
          </tr>
        </thead>
        <tbody>
	  <?php
		$entry = 1;
		$res = ninja_driver_applications();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td><a href="approve_driver.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Approve</a></td>';
			echo '<td>Driver</td>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td>'.$row['Info'].'</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
		$res = ninja_company_applications();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td><a href="approve_company.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Approve</a></td>';
			echo '<td>Company</td>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td>'.$row['Info'].'</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
        </tbody>
      </table>
    </div>
    </div>
		<div id = "ManageAccountContent" style = "display:none;">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>Create a Account</h1>
      </div><br /><br />
			<div class = "container">
				<h2>Enter new account info</h2>
				<form action = "create_account.php" method = "post">
					<div class = "row">
						<div class = "col">
							<label for="fName">First Name</label>
							<input type = "text" class = "form-control" id = "FName" placeholder = "First name" name = "FName"/>
							</div>
							<div class = "col">
								<label for="lName">Last Name</label>
								<input type = "text" class = "form-control" id = "LName" placeholder = "Last name" name = "LName"/>
								</div>
						</div>
						<div class = "row">
							<div class = "col">
								<label for="newEmail">Email</label>
								<input type = "email" class = "form-control" id = "Email" placeholder = "Enter email" name = "Email"/>
								</div>
								<div class = "col">
									<label for = "accountType">Account Type</label>
									<select id = "accountType" class = "form-control" name = "UserType">
										<option selected>Driver</option>
										<option>Sponsor</option>
									</select>
									<label for = "company">Company (for sponsor accounts only)</label>
									<select id = "company" class = "form-control" name = "CompanyID">
									<?php
										$companies = ninja_companies();
										$first = true;
										while ($row = $companies->fetch_assoc())
										{
											echo '<option value = "'.$row['CompanyID'].'"';
											if ($first)
												echo ' selected = "selected" ';
											echo '>';
											echo $row['Name'].' ('.$row['CompanyID'].')';
											echo '</option>';
											$first = false;
										}
									?>
									</select>
								</div>
							</div><br />
							<button class = "btn btn-primary" action = "submit">Create Account</button>
					</form>
				</div>
    </div>
		<div id = "ViewAccountContent" style = "display:none;">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>View Drivers and Sponsors</h1>
      </div>
			<div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
	    			<th>Account Type</th>
            <th>Company</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
	    <th>Delete</th>
          </tr>
        </thead>
        <tbody>
	  <?php
		$entry = 1;
		$res = ninja_drivers();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td>Driver</td>';
			$cid = $row['CompanyID'];
			echo '<td>'.ninja_company_name($cid).'</td>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td><a href="delete_account.php?UID='.$row['UserID'].'">Delete Account</a></td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
		$res = ninja_sponsors();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			echo '<td>Sponsor</td>';
			$cid = $row['CompanyID'];
			echo '<td>'.ninja_company_name($cid).'</td>';
			echo '<td>'.$row['FName'].'</td>';
			echo '<td>'.$row['LName'].'</td>';
			echo '<td>'.$row['Email'].'</td>';
			echo '<td><a href="delete_account.php?UID='.$row['UserID'].'">Delete Account</a></td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
					</tbody>
			</table>
			</div>
    </div>
		<div id = "ViewCompanyContent" style = "display:none;">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>View Companies</h1>
      </div>
			<div class = "table-responsive-lg" style="overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Sponsors</th>
            <th>Drivers</th>
	    <th>Delete</th>
          </tr>
        </thead>
        <tbody>
	  <?php
		$entry = 1;
		$res = ninja_companies();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			$cid = $row['CompanyID'];
			echo '<td>'.$cid.'</td>';
			echo '<td>'.ninja_company_name($cid).'</td>';
			echo '<td>'.ninja_company_sponsor_count($cid).'</td>';
			echo '<td>'.ninja_company_driver_count($cid).'</td>';
			echo '<td><a href="delete_company.php?CID='.$cid.'">Delete Company</a></td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
	  ?>
					</tbody>
			</table>
			</div>
    </div>
    <div id = "OrderItemContent" style = "display:none;">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>Create an Item</h1>
      </div>
			<div class = "container">
				<h2>Enter item info here:</h2>
				<form>
					<div class = "row">
						<div class = "col">
							<label for = "itemName">Item Name</label>
							<input type = "text" id = "itemName" class = "form-control" placeholder="Name" />
						</div>
						<div class = "col">
							<label for = "itemPrice">Item Price</label>
							<input type = "text" id = "itemPrice" class = "form-control" placeholder="Price" />
						</div>
					</div>
					<div class = "row">
						<div class = "col">
							<label for = "itemQuantity">Item Amount</label>
							<input type = "text" id = "itemQuantity" class = "form-control" placeholder="Quantity" />
						</div>
						<div class = "col">
							<label for = "catalog">Catalog</label>
							<input type = "text" id = "catalog" class = "form-control" placeholder="Catalog Name" />
						</div>
					</div><br />
					<div class = "row">
						<div class = "col">
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="choosePicture">
								<label class="custom-file-label" for="choosePicture">Choose Picture</label>
							</div>
							</div>
					</div><br />
					<button class = "btn btn-primary">Submit</button>

					</form>
				</div>
    </div>
    <div id = "AnalyticsContent" style = "display: none;">
      <div class = "jumbotron">
        <h1>Analytics</h1>
      </div>
			<p>
				Future content for data visualization on orders will apear here.
			</p>
    </div>
  </div>
</div>
</body>

<script>
function showContent(i){
	var applications = document.getElementById("Applications");
  var manageAccounts = document.getElementById("ManageAccountContent");
  var viewAccounts = document.getElementById("ViewAccountContent");
	var createOrders = document.getElementById("OrderItemContent");
	var analytics = document.getElementById("AnalyticsContent");
	var viewCompanies = document.getElementById("ViewCompanyContent");
	if(i == 1){
		applications.style.display = "block";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "none";
		createOrders.style.display = "none";
		analytics.style.display = "none";
		viewCompanies.style.display = "none";
	}
	else if(i == 2){
		applications.style.display = "none";
		manageAccounts.style.display = "block";
		viewAccounts.style.display = "none";
		createOrders.style.display = "none";
		analytics.style.display = "none";
		viewCompanies.style.display = "none";
	}
	else if(i == 3){
		applications.style.display = "none";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "block";
		createOrders.style.display = "none";
		analytics.style.display = "none";
		viewCompanies.style.display = "none";
	}
	else if(i == 4){
		applications.style.display = "none";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "none";
		createOrders.style.display = "block";
		analytics.style.display = "none";
		viewCompanies.style.display = "none";
	}
	else if(i == 5){
		applications.style.display = "none";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "none";
		createOrders.style.display = "none";
		analytics.style.display = "block";
		viewCompanies.style.display = "none";
	}
	else if(i == 6){
		applications.style.display = "none";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "none";
		createOrders.style.display = "none";
		analytics.style.display = "none";
		viewCompanies.style.display = "block";
	}
	else{
		applications.style.display = "none";
		manageAccounts.style.display = "none";
		viewAccounts.style.display = "none";
		createOrders.style.display = "none";
		analytics.style.display = "none";
		viewCompanies.style.display = "none";
	}
}
</script>
