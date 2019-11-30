  <!--Header Navbar-->
<nav class = "navbar navbar-expand-lg" style = "background-image: linear-gradient(to bottom right, #071461, #0B3583);">
  <a class = "navbar-brand text-light" href = "sponsor_view.php">What the Truck! Sponsor</a>
  <ul class = "navbar-nav mr-auto">
    <li class = "nav-item text-light bg-dark">
    </li>
  </ul>


  <!--Account Picture and Name-->
  <ul id = "profilepic" class = "navbar-nav navbar-right">
  <li class = "nav-item">
	<a class = "nav-link text-light" href = "contact_admin.php">Contact Us</a>
	</li>
  <li class = "nav-item" style = "padding-right: 10px;">
	<a class = "nav-link text-light" href = "logout.php">Logout</a>
  </li>
  <li class = "nav-item">
  <div id = "ProfileName" onclick = "location.href = 'view_profile.php';"style = "padding-left: 20px; color: white; border-left: white 1px solid">
      <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($pfp).'"'?> /></span>
        <p style = "vertical-align: middle; display: inline-block; margin: auto;">
          <?php echo htmlspecialchars(ninja_name($uid)); ?>
        </p>
      </div>
  </li>
  </ul>
</nav>

