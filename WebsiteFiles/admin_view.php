
<?php
        include "db_ninja.php";
        session_start();

        if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Admin')
        {
                header("location: DesktopSite.php");
                exit;
        }

        $uid = $_SESSION['UserID'];
        $fullname = ninja_name($uid);
        $pfp = ninja_pfp($uid);
?>


<html>
<?php include "htmlhead.php"?>
<body>

<?php include "admin_header.php"?>

<!--Navbar-->
<nav class = "navbar navbar-expand-lg navbar-light bg-light">
  <ul class = "nav nav-pills" id = "tabs">
    <li class = "nav-item">
	<a class = "nav-link active" data-toggle = "pill" href = "#Dashboard-Tab" aria-selected = "true">Dashboard</a>
    </li>
    <li class = "nav-item">
      <a class = "nav-link" id = "application-tab" data-toggle = "pill" href = "#applicationtab" aria-selected = "true">View Applications</a>
    </li>
    <li class = "nav-item">
      <a class = "nav-link" id = "driver-tab" data-toggle = "pill" href = "#driverTab" aria-selected = "false">View Drivers</a>
    </li>
    <li class = "nav-item">
      <a class = "nav-link" id = "sponsor-tab" data-toggle = "pill" href = "#asponsorTab" aria-selected = "false">View Sponsors</a>
    </li>
    <li class = "nav-item">
      <a class = "nav-link" id = "companies-tab" data-toggle = "pill" href = "#companiesTab" aria-selected = "false">View Companies</a>
    </li>
    <li class = "nav-item">
	<a class = "nav-link" id = "catalog-tab" data-toggle = "pill" href = "#catalogTab" aria-selected = "false">View Catalogs</a>
    </li>
  </ul>
</nav>



<div class = "tab-content">
  <!--View Applications-->
	<div class = "tab-pane fade-show active" id = "Dashboard-Tab">
		<div class = "jumbotron">
			<h2>Admin Dashboard</h2>
		</div>
		<div class = "container-fluid">
			<div class = "row">
				<div class = "col-lg-6">
					<div class = "card" style = "margin-bottom: 20px;">
						<div class = "card-header"><h4>Create Account</h2></div>
						<div class = "card-body">
							<form action="create_account.php" class = "needs-validation" method="post" novalidate>
                             			           <div class="row">
                           		                     <div class="col-md-8">
                                        	                <label for="fName">First Name</label>
                                                	        <input type="text" class="form-control" id="FName" placeholder="First name" name="FName" required>
                              		                        <div class = "invalid-feedback">Enter a valid first name</div>
                                        		     </div>
                                   			   </div>
                                        		<div class = "row">
                                               			 <div class="col-md-8">
                                                	  	      <label for="lName">Last Name</label>
                                      				      <input type="text" class="form-control" id="LName" placeholder="Last name" name="LName" required>
                                              			      <div class = "invalid-feedback">Enter a valid last name</div>
                                        		        </div>
                                    			    </div>
                                      			  <div class="row">
                                             			   <div class="col-md-8">
                                      			                  <label for="newEmail">Email</label>
                                                		        <input type="email" class="form-control" id="Email" placeholder="Enter email" name="Email" required>
                                        		                <div class = "invalid-feedback">Enter a valid email</div>
                              			                  </div>
                  			                      </div>
                                		        <div class = "row">
                          	                       <div class="col-md-8">
                                        	                <label for="accountType">Account Type</label>
                                	                        <select id="accountType" class="form-control" name="UserType" required>
                                                	                <option selected="">Driver</option>
                                       		                         <option>Sponsor</option>
                                                	        </select>
                                            		    </div>
                             			           </div>
                           		             <div class = "row">
                                        	        <div class = "col-md-8">
                                                	        <label for="company">Company (for sponsor accounts only)</label>
                                             	           <select id="company" class="form-control" name="CompanyID">
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
                                        	</div>
                                                        <br>
                                                        <button class="btn btn-primary" action="submit">Create Account</button>
     				   </form>
						</div>
					</div>
				</div><br><br><br>
				<div class = "col-lg-6">
                                        <div class = "card">
                                                <div class = "card-header"><h4>Create New Company</h2></div>
                                                <div class = "card-body">
						      <form action="create_company.php" class = "needs-validation" method="post" novalidate>
        		                                <div class="row">
                        		                        <div class="col-md-8">
                                       		                 <label for="newName">Name</label>
                                        	                <input type="text" class="form-control" id="newName" placeholder="Enter company name" name="Name" required>
                                                	        <div class = "invalid-feedback">Enter a valid name</div>
                                          		      </div>
                               			         </div>
                                                        <br>
                                                        <button class="btn btn-primary" action="submit">Create Empty Company</button>
      						       </form>
                                                </div>
                                        </div>

				<br>
				
                                        <div class = "card">
                                                <div class = "card-header"><h4>Add New Item</h2></div>
                                                <div class = "card-body">
							<form action="create_item.php" method = "post">
							      <div class = "row">
							        <div class = "col-md-8">
							          <label for = "WebSource">Source</label>
                	        				  <select name = "WebSource" class = "form-control" id = "WebSource">
								  <!-- <option value = "Amazon">Amazon</option> -->
								  <option value = "Ebay" selected>Ebay</option>
								  </select>
							        </div>
							      </div>
							      <div class = "row">
							        <div class = "col-md-8">
							          <label for = "LinkInfo">Link Info</label>
								          <input type = "text" name = "LinkInfo" id = "LinkInfo" class = "form-control" placeholder = "Link Info" />
							        </div>
							      </div><br />
							      <div class = "row">
							        <div class = "col-md-8">
							          <button class = "btn btn-primary">Add to base catalog</button>
							        </div>
							      </div>
						    </form>
							          <button class = "btn btn-primary" onclick = "location.href = 'base_catalog.php'">View base catalog</button>
                                                </div>
                                        </div>
				</div><br>
			</div>
		</div>
	</div>
  <div class = "tab-pane fade" id = "applicationtab" role = "tabpanel">
	<div class = "jumbotron" style = "margin: 0;">
		<h2>View Applications</h2>
	</div>
 <div class="table-responsive-lg" style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
	    <th>Type</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Info</th>
            <th>Accept</th>
            <th>Decline</th>
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
			echo '<th>Driver</th>';
                        echo '<td>'.$row['FName'].'</td>';
                        echo '<td>'.$row['LName'].'</td>';
                        echo '<td>'.$row['Email'].'</td>';
                        echo '<td>'.$row['Info'].'</td>';
                        echo '<td><a href="approve_driver.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Approve</a></td>';
                        echo '<td><a href="reject_application.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Reject</a></td>';
                        echo '</tr>';
                        $entry = $entry + 1;
                }
                $res = ninja_company_applications();
                while ($row = $res->fetch_assoc())
                {
                        echo '<tr>';
                        echo '<th>'.$entry.'</th>';
			echo '<th>Company</th>';
                        echo '<td>'.$row['FName'].'</td>';
                        echo '<td>'.$row['LName'].'</td>';
                        echo '<td>'.$row['Email'].'</td>';
                        echo '<td>'.$row['Info'].'</td>';
                        echo '<td><a href="approve_company.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Approve</a></td>';
                        echo '<td><a href="reject_application.php?FName='.$row['FName'].'&LName='.$row['LName'].'&Email='.$row['Email'].'">Reject</a></td>';
                        echo '</tr>';
                        $entry = $entry + 1;
                }
          ?>

          </tbody>
      </table>
    </div>
  </div>

  <!--Manage Accounts-->
  <div class = "tab-pane fade" id = "manageAccountTab" role = "tabpanel">
    <div class = "container-fluid">
      <form action="create_account.php" class = "needs-validation" method="post" novalidate>
					<div class="row">
						<div class="col-md-4">
							<label for="fName">First Name</label>
							<input type="text" class="form-control" id="FName" placeholder="First name" name="FName" required>
							<div class = "invalid-feedback">Enter a valid first name</div>
						</div>
    				        </div>
  				        <div class = "row">
                				<div class="col-md-4">
  							<label for="lName">Last Name</label>
  							<input type="text" class="form-control" id="LName" placeholder="Last name" name="LName" required>
							<div class = "invalid-feedback">Enter a valid last name</div>
  						</div>
              				</div>
					<div class="row">
						<div class="col-md-4">
							<label for="newEmail">Email</label>
							<input type="email" class="form-control" id="Email" placeholder="Enter email" name="Email" required>
							<div class = "invalid-feedback">Enter a valid email</div>
						</div>
			                </div>
              				<div class = "row">
  						 <div class="col-md-4">
							<label for="accountType">Account Type</label>
							<select id="accountType" class="form-control" name="UserType" required>
								<option selected="">Driver</option>
								<option>Sponsor</option>
							</select>
						</div>
					</div>
					<div class = "row">
						<div class = "col-md-4">
							<label for="company">Company (for sponsor accounts only)</label>
							<select id="company" class="form-control" name="CompanyID">
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
              				</div>
							<br>
							<button class="btn btn-primary" action="submit">Create Account</button>
	</form>
    </div>
  </div>

  <!--Manage Companies-->
  <div class = "tab-pane fade" id = "manageCompanyTab" role = "tabpanel">
    <div class = "container-fluid">
      <form action="create_company.php" class = "needs-validation" method="post" novalidate>
					<div class="row">
						<div class="col-md-4">
							<label for="newName">Name</label>
							<input type="text" class="form-control" id="newName" placeholder="Enter company name" name="Name" required>
							<div class = "invalid-feedback">Enter a valid name</div>
						</div>
			                </div>
							<br>
							<button class="btn btn-primary" action="submit">Create Empty Company</button>
	</form>
    </div>
  </div>

  <!--View Drivers-->
  <div class = "tab-pane fade" id = "driverTab" role = "tabpanel">
        <div class = "jumbotron" style = "margin: 0;">
                <h2>View Drivers</h2>
        </div>

    <div class = "table-responsive-lg" style = "overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Company</th>
            <th>Email</th>
	    <th>Modify Account</th>
            <th>Delete Account</th>
          </tr>
	</thead>
	<tbody>
		<?php
                $entry = 1;
                $res = ninja_drivers();
                while ($row = $res->fetch_assoc())
                {
                        $cid = $row['CompanyID'];
                        echo '<tr>';
                        echo '<th>'.$entry.'</th>';
                        echo '<td>'.$row['FName'].'</td>';
                        echo '<td>'.$row['LName'].'</td>';
                        echo '<td>'.ninja_company_name($cid).'</td>';
                        echo '<td>'.$row['Email'].'</td>';
			echo '<td><a href="admin_edit_driver_profile.php?DID='.$row['UserID'].'">Edit Account</a></td>';
                        echo '<td><a href="delete_account.php?UID='.$row['UserID'].'">Delete Account</a></td>';
                        echo '</tr>';
                        $entry = $entry + 1;
                }
		?>
        </tbody>
      </table>
    </div>
  </div>



  <!--View Sponsors-->
  <div class = "tab-pane fade" id = "asponsorTab" role = "tabpanel">
        <div class = "jumbotron" style = "margin:0;">
                <h2>View Sponsors</h2>
        </div>

    <div class = "table-responsive-lg" style = "overflow-x:auto;">
      <table class = "table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Company</th>
            <th>Email</th>
	    <th>Modify Account</th>
            <th>Delete Account</th>
          </tr>
	</thead>
	<tbody>
	<?php
	$entry = 1;
	$res = ninja_sponsors();
                while ($row = $res->fetch_assoc())
                {
                        echo '<tr>';
                        echo '<th>'.$entry.'</th>';
                        $cid = $row['CompanyID'];
			if(strlen($row['FName']) != 0)
				echo'<td>'.$row['FName'] . '</td>';
			else
				echo '<td>N/A</td>';
			if(strlen($row['LName']) != 0)
                        	echo '<td>'.$row['LName'].'</td>';
			else
				echo '<td>N/A</td>';
                        echo '<td>'.ninja_company_name($cid).'</td>';
                        echo '<td>'.$row['Email'].'</td>';
			echo '<td><a href="admin_edit_sponsor_profile.php?SID='.$row['UserID'].'">Edit Account</a></td>';
			echo '<td><a href="delete_account.php?UID='.$row['UserID'].'">Delete Account</a></td>';
                        echo '</tr>';
                        $entry = $entry + 1;
                }
          ?>
        </tbody>
      </table>
    </div>
  </div>

        <!--EntryNum, Name, Company Name, Num of Items, View Catalog Link, Delete Catalog-->
  <div class = "Tab-pane fade" id = "catalogTab" role = "tabpanel">
        <div class = "jumbotron" style = "margin:0;">
                <h2>View Catalogs</h2>
        </div>
        <div class = "table-responsive-lg" style = "overflow-x: auto;">
                <table class = "table table-hover">
                        <thead>
                                <tr>
                                        <th>#</th>
                                        <th>Catalog Name</th>
                                        <th>Company</th>
                                        <th>Number of Items</th>
					<th>Visible to Drivers</th>
                                        <th>View Catalog</th>
                                        <th>Delete Catalog</th>
                                </tr>
			</thead>
			<tbody>
	<?php

	$catalogs = ninja_all_catalogs();
	$num = 1;
	while ($row = $catalogs->fetch_assoc())
	{
		echo '<tr>';
		echo '<th>';
		echo $num;
		echo '</th>';
		echo '<td>';
		echo $row['CatalogName'];
		echo '</td>';
		echo '<td>';
		echo $row['CompanyName'];
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
                        </tbody>
                </table>
        </div>
</div>



<!--View Company-->
  <div class = "tab-pane fade" id = "companiesTab" role = "tabpanel">
        <div class = "jumbotron" style = "margin: 0";>
                <h2>View Companies</h2>
        </div>

    <div class="table-responsive-lg" style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Number of Sponsors</th>
            <th>Number of Drivers</th>
	    <th>Delete Company</th>
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
                        	echo '<td><a href="delete_company.php?CID='.$cid.'">Delete</a></td>';
                        	echo '</tr>';
                        	$entry = $entry + 1;
                	}	
          	?>
	</tbody>
</table>
</div>
  </div>




  <!--Create Items-->
  <div class = "tab-pane fade" id = "itemTab" role = "tabpanel">
    <div class = "container-fluid">
      <form>
      <div class = "row">
        <div class = "col-md-4">
          <label for = "webSource">Source</label>
          <input type = "text" id = "webSource" class = "form-control" placeholder="Web Source" />
        </div>
      </div>
      <div class = "row">
        <div class = "col-md-4">
          <label for = "linkInfo">Link Info</label>
          <input type = "text" id = "linkInfo" class = "form-control" placeholder = "Link Info" />
        </div>
      </div><br />
      <div class = "row">
        <div class = "col-md-4">
          <button class = "btn btn-primary">Add to base catalog</button>
        </div>
      </div>
    </form>
    </div>
  </div>

</div>
</body>
</html>


<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
