<?php
	include_once "db_ninja.php";
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];

	$image = ninja_pfp($uid);
	
	$cid = ninja_current_driver_company($uid);

	$total = ninja_points($uid, $cid);

 ?>

<nav class = "navbar sticky-top navbar-expand-lg navbar-light" style = "background-image:linear-gradient(to right, #071461, #0B358E); box-shadow: 8px 8px 8px 5px rgba(0, 0, 255, .1);" >

    <a class = "navbar-brand" style = "color:white;" onclick = "window.location.href = 'driver_view.php';">
	<img width = "30px" height = "30px" src = "Assets/logo-blue.png">What the Truck!<a>
<button class = "navbar-toggler" style = "background-color: white;" type = "button" data-toggle="collapse" data-target = "#navbarNav" aria-expanded="false" aria-controls = "navbarNav" aria-label = "Toggles navbar">
	<span class = "navbar-toggler-icon" ></span>
</button>
<!--Navbar Links-->
 <div class = "collapse navbar-collapse" id = "navbarNav">
  <ul class = "navbar-nav mr-auto">
    <li class = "nav-item" style = "color:white;">
	<a class = "nav-link" style = "color:white;" href = "driver_sponsor_list.php">Apply for Sponsors</a>
    </li>
	<li class = nav-item">
		<a class = "nav-link" style = "color: white;" href = "driver_history.php">History</a>
	</li>
    <li class = "nav-item" style = "color: white;">
      <a class = "nav-link" style = "color:white;" href = "contact_admin.php">Contact Us</a>
    </li>
  </ul>
</div>

<!--Profile-->  
<ul id = "profilepic" class = "nav navbar-nav navbar-right" onclick = "location.href = 'view_profile.php';" style = "display: inline-block; float: right;">
<li>
<div id = "ProfileName" style = "padding-left: 20px; color: white; border-left: white 1px solid">
    <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($image).'"'?> /></span>
      <p style = "vertical-align: middle; display: inline-block; margin: auto;">
        <?php echo htmlspecialchars(ninja_name($uid)); ?><br />Points: <?php echo htmlspecialchars($total); ?>
      </p>
    </div>
</li>
</ul>
<!--</button>-->
</nav>

<script>
$(function () {
 $('[data-toggle="tooltip"]').tooltip()
})
</script>
<div id = "buttonContainer">
<button class = "btn btn-success" onclick = "window.open('Assets/wtt-alpha-s8.apk')" data-toggle="tooltip" data-placement="left" title="Download App" id = "circular"><span class = "oi oi-data-transfer-download"></span></button><br>
<button class = "btn btn-warning" onclick = "location.href = 'driver_cart.php'"data-toggle="tooltip" data-placement="left" title="View Cart" id = "circular"><span style = "color:white;"class = "oi oi-cart"></span></button><br>
<button class = "btn btn-danger" onclick = "location.href = 'logout.php'" data-toggle="tooltip" data-placement="left" title="Log Off" id = "circular"><span class = "oi oi-account-logout"></span></button>
</div>












