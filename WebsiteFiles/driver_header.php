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

<nav class = "navbar navbar-expand-lg navbar-default" style = "background-image:linear-gradient(to right, #071461, #0B358E); box-shadow: 8px 8px 8px 5px rgba(0, 0, 255, .1);" >
  <button type = "button" class = "btn btn-outline-light" onclick = "location.href = 'view_profile.php';"><div class = "ProfileName" >
    <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($image).'"'?> /></span>
      <p style = "vertical-align: middle; display: inline-block; margin: auto;">
        <?php echo htmlspecialchars(ninja_name($uid)); ?><br />Points: <?php echo htmlspecialchars($total); ?>
      </p>
    </div></button>
  <div class = "navbar nav-right" id = "navbarNav">
  <ul class = "navbar-nav">
    <li class = "nav-item" style = "color: white;">
      <a class = "nav-link" style = "color:white;" href = "contact_admin.php">Contact Us</a>
    </li>
		

    <li class = "nav-item" style = "color:white;">
      <a class = "nav-link" style = "color:white;" href = "logout.php">Log Off</a>
    </li>

    <li class = "nav-item" style = "color:white;">
	<a class = "nav-link" style = "color:white;" href = "#">Download Mobile App</a>
    </li>

    <li class = "nav-item" style = "color:white;">
	<a class = "nav-link" style = "color:white;" href = "driver_sponsor_list.php">Apply for Sponsors</a>
    </li>
  </ul>
  </div>
</nav>

