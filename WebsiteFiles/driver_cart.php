<?php

include "db_ninja.php";
session_start();
if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
{
	header("location: logon.php");
	exit;
}
?>
<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>

<div id = "cartBody">
  <div class = "jumbotron">
    <h1>User's Cart</h1>
    </div>
    <div class = "container-fluid">
      <div class = "row" style =>
        <div class = "col-md-3">
          <img src = "Assets/DefaultPicture.jpg" />
          </div>
          <div class = "col-md-6" style = "vertical-align: middle;">
            <p style = "top: 50%;">
              Item Name
            </p>
            </div>
            <div class = "col-md-3" style = "text-align: center;">
              <p>
                Price
              </p>
              </div>
        </div>
        <hr />
      </div>
    </div>

<div id = "cartSideBar" style = "background-image: linear-gradient(to bottom right, #E5E5E5, #FFFFFF);">
<br/><br/><br/><br/><br/><br/>
<h1 style = "text-align:center; color: black;">Options</h1><hr>
  <ul class = "navbar-nav" id = "buttonList">
    <li class = "nav-item">
      <button class = "btn btn-primary">Checkout</button>
      </li><br />
      <li class = "nav-item">
        <button class = "btn btn-primary">Keep Shopping</button>
        </li>
    </ul>
</div>

</body>
