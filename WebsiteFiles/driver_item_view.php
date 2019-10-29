<html style = "height: 100%;">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
  <title>What the Truck!</title>
<nav class = "navbar navbar-expand-lg navbar-default" style = "background-image:linear-gradient(to right, #071461, #0B358E); box-shadow: 8px 8px 8px 5px rgba(0, 0, 255, .1);" >
  <button type = "button" class = "btn btn-outline-light" onclick = "location.href" = "DesktopSite.html"><div class = "ProfileName" >
    <span class = "Accountpicture" style = "vertical-align: middle; margin: auto;display: inline-block;"><img width = "40px" height="40px" src =<?php echo '"data:image/png;base64,'.base64_encode($image).'"'?> /></span>
      <p style = "vertical-align: middle; display: inline-block; margin: auto;">
        <?php echo htmlspecialchars(ninja_name($uid)); ?><br />Points: <?php echo htmlspecialchars($total); ?>
      </p>
    </div></button>
  <div class = "navbar nav-right" id = "navbarNav">
  <ul class = "navbar-nav">
    <li class = "nav-item">
      <a class = "nav-link" style = "color: white;">Contact Us</a>
    </li>

		<li class = "nav-item" >
			<a class = "nav-link" style = "color:white;" href = "3">Edit Account Info</a>
		</li>

    <li class = "nav-item">
      <a class = "nav-link" style = "color:white;" href = "logout.php">Log Off</a>
    </li>
  </ul>
  </div>
</nav>
<br/>
<div class = "container">
  <br /><br />
  <div class = "row">
    <div class = "col-md-6">
      <h1>Item Name</h1><br />
    </div>
  </div>
  <div class = "row">
    <div class = "col-md-5" style = "text-align:center;">
      <img src = "Assets/DefaultPicture.jpg"/>
    </div>
    <div class = "col-lg-3 justify-content-center">
      <ul class = "list-group">
        <li class = "list-group-item">
          <p>
            Price: Price
          </p>
        </li>
        <li class = "list-group-item">
          <p>
            Available?: Maybe
          </p>
        </li>
      </ul><br />
      <button class = "btn btn-primary">Buy Now</button>
      <button class = "btn btn-secondary">Add to Cart</button>
    </div>
  </div><br /><br />
  <div class = "row">
    <div class = "col-lg-9">
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.  </p>
    </div>
  </div>
  <div class = "row">
    <div class = "col-lg-6">
      <button class = "btn btn-primary">Back to Home</button>
    </div>
  </div>
</div>

</body>
