<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
{
	header("location: DesktopSite.php");
	exit;
}
$uid = $_SESSION['UserID'];

$name = ninja_name($uid);
$pfp = ninja_pfp($uid);

if ($_SESSION['UserType'] === "Driver")
{
	$cid = ninja_current_driver_company($uid);
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$cid = $_POST['DriverSponsor'];
		ninja_set_driver_current_company($uid, $cid);
	}
	$phone = ninja_phone_dashes($uid);
	$company = ninja_company_name($cid);
	$spent = ninja_point_subtractions($uid, $cid);
	$earned = ninja_point_additions($uid, $cid);
	$address = ninja_address_oneline($uid);
	$companies = ninja_driver_company_list($uid);
}

?>

<html style = "height: 100%;">
<?php include "htmlhead.php";?>
<body style = "height: 100%;">
  <title><?php echo $name ?></title>
<?php
if($_SESSION['UserType'] === "Driver")
	 include "driver_header.php";
else if($_SESSION['UserType'] === "Admin")
	include "admin_header.php";
else
	include "sponsor_header.php";
?>
<div class = "jumbotron">
   <h1><?php echo $name?></h1>
</div>
<div class = "container" style = "margin: 0 auto;">
  <div class = "row justify-content-center">
  </div>
  <div class = "row justify-content-center" style = "margin: auto;">
      <div class = "col-md-6" style = "border-right: 1px gray solid;">
          <div class = "ProfileName">
	  <h2>Profile Info</h2>
          <span id = "Accountpicture"><img width = "350px" src =<?php echo '"data:image/png;base64,'.base64_encode($pfp).'"' ?> /></span><br />
	  </div>
       </div>
	  <div class = "col-md-6" style = "margin:auto;">
            <p id = "AccountText">
              <?php echo ""; ?><br />
            </p>
		<?php
		if ($_SESSION['UserType'] === "Driver")
		{
			echo "<p id = 'PhoneNumber'>";
			echo "Phone: ".$phone;
			echo "</p>";
			echo "<p id = 'Address'>";
			echo "Address: ".$address;
			echo "</p>";

			if (!is_bool($companies))
			{
				echo "<p id = 'CurrentCompany'>";
				echo "Current Company: ".$company;
				echo "</p>";
                      		
				echo '<div class = row>';
                        	echo '<div class = "col">';
                       		echo '<div class = "form-group">';
				echo '<form method = "post">';
                       		echo  '<label for = "DriverorSponsor"></label>';
                	        echo  '<select name = "DriverSponsor" class = "form-control" id = "DriverorSponsor" selected = "'.$company.'" onchange = "this.form.submit()">';
				echo '<option>Choose a Company</option>';
				$companies->data_seek(0);
				while ($row = $companies->fetch_assoc())
				{
					echo '<option value = "'.$row['CID'].'"';
					if ($cid == $row['CID'])
						echo 'selected="selected"';
					echo '>';
					echo $row['CName'];
					echo '</option>';
				}
                	        echo  '</select>';
				echo '</form>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			else
			{
				echo "<p id = 'CurrentCompany'>";
				echo "Current Company: No companies!";
				echo "</p>";
			}

			echo "<p id = 'SpentPoints'>";
			echo "Total Points Spent: ".$spent;
			echo "</p>";
			echo "<p id = 'EarnedPoints'>";
			echo "Total Points Earned: ".$earned;
			echo "</p>";
			echo "<p id = 'DriverSpace'>";
			echo "";
			echo "</p>";
		}
		else if ($_SESSION['UserType'] === "Admin")
		{
			$earned = ninja_money_earned($uid);
			echo "<p id = 'EarnedMoney'>";
			echo "Total Money Earned: $".$earned," USD";
			echo "</p>";
		}
		else
		{
			$cid = ninja_sponsor_company_id($uid);
			$cname = ninja_company_name($cid);
			echo "<p id = 'SponsorName' style = 'font-size:25px;'>Name: $name;</p>";
			echo "<p id = 'Company' style = 'font-size:25px;'>Company Name: $cname</p>";
		}
		?>
		
          </div>
	<br><br>
          <form style = "margin: 0 auto; margin-top:20px;" action = "logon.php">
            <div class = "row justify-content-center">
              <button type = submit class = "btn btn-primary-light btn-block">Return to Home</button>
              <a href = "edit_profile.php" class = "form-text" style = "font-size: 12px;">Edit Information</a>
            </div>
          </form>
        </div>
      </div>

      <br />
