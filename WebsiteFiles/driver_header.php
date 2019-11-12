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

<nav class = "navbar navbar-expand-lg navbar-light" style = "background-image:linear-gradient(to right, #071461, #0B358E); box-shadow: 8px 8px 8px 5px rgba(0, 0, 255, .1);" >

    <a class = "navbar-brand" style = "color:white;" onclick = "window.location.href = 'driver_view.php';">
	<img width = "30px" height = "30px" src = "Assets/logo-blue.png">What the Truck!<a>
<button class = "navbar-toggler" style = "background-color: white;float: right;" type = "button" data-toggle="collapse" data-target = "#navbarNav" aria-expanded="false" aria-controls = "navbarNav" aria-label = "Toggles navbar">
	<span class = "navbar-toggler-icon" ></span>
</button>
<!--Navbar Links-->
 <div class = "collapse navbar-collapse" id = "navbarNav">
  <ul class = "navbar-nav" id = "navbarNav">
    <li class = "nav-item" style = "color:white;">
	<a class = "nav-link" style = "color:white;" href = "driver_sponsor_list.php">Apply for Sponsors</a>
    </li>
	<li class = nav-item">
		<a class = "nav-link" style = "color: white;" href = "driver_history.php">Account History</a>
	</li>
	<li class = "nav-item">
		<a class = "nav-link" style = "color:white;" href = "driver_cart.php">View Cart</a>
	</li>

    <li class = "nav-item" style = "color: white;">
      <a class = "nav-link" style = "color:white;" href = "contact_admin.php">Contact Us</a>
    </li>

    <li class = "nav-item" style = "color:white;">
        <a class = "nav-link" style = "color:white;" href = "Assets/Files/wtt-alpha-s7.apk" download>Download Mobile App</a>
    </li>

 <li class = "nav-item" style = "color:white;">
      <a class = "nav-link" style = "color:white;" href = "logout.php">Log Off</a>
    </li>


  </ul>
</div>

<!--Profile-->  
<ul class = "nav navbar-nav navbar-right" onclick = "location.href = 'view_profile.php';" style = "display: inline-block; float: right;">
<li>
<div class = "ProfileName" style = "padding-left: 20px; color: white; border-left: white 1px solid">
    <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($image).'"'?> /></span>
      <p style = "vertical-align: middle; display: inline-block; margin: auto;">
        <?php echo htmlspecialchars(ninja_name($uid)); ?><br />Points: <?php echo htmlspecialchars($total); ?>
      </p>
    </div>
</li>
</ul>
<!--</button>-->
</nav>

