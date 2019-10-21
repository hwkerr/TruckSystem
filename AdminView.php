<?php include "../inc/dbinfo.inc"; ?>
<!DOCTYPE html>
<html style = "height: 100%;">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel = "stylesheet" href = "style.css">
</head>
<body style = "height: 100%;">
  <title>What the Truck!</title>
<div id = "homeBody">
    <!--SideNav-->
    <nav class = "justify-content-center" id = "sideNav">
      <div id = "AccountProfile">
        <button type = "button" style = "border-radius: 0rem; color:white;"class = "btn btn-link btn-block" onclick = "location.href" = "DesktopSite.html">
          <div class = "ProfileName" >
          <span id = "Accountpicture"><img width = "60px" src ="Assets/ProfilePicture.jpg" /></span><br />
            <p id = "AccountText">
              Andrew Zeringue<br />
            </p>
          </div>
        </button>
        <hr style = "border-top: 1px solid white;"/>
      </div>
        <ul class = "navbar-nav" id = "buttonList">
          <li class = "nav-item">
            <button type = "button" onclick = "showCatalogue()"
            class = "btn btn-outline-light">
              Create Account
            </button>
            <br /><br />
          </li>
          <li class = "nav-item" id = "buttonList">
            <button type = "button" onclick = "showAccountManagement()"
            class = "btn btn-outline-light">Manage Account
          </button>
          <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showDriver()"
            class = "btn btn-outline-light ">
              View Sponsors/Drivers
            </button>
            <br /><br />
          </li>
          <li class = "nav-item">
            <button type = "button" onclick = "showCompany()"
            class = "btn btn-outline-light ">
              Create Order/Item
            </button>
            <br /><br />
          </li>
          <li class = "nav-item" id = "buttonList">
            <button type = "button" onclick = "showAnalytics()"
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
            <a class = "nav-link" href = "DesktopSite.html">Log Off</a>
          </li>
        </ul>
      </div>

    </nav>
    <div class = "CatalogContent" id = "CatalogInfo" style = "display: none;">
      <div class = "jumbotron" style = "margin-bottom: 0px;">
        <h1>Catalog info</h1>
      </div>
    </div>
    <div id = "DriverContent">
      <div class = "jumbotron" style = "margin-bottom: 0;">
        <h1>Drivers</h1>
      </div>
    </div>
    <div class = "CompanyContent" id = "CompanyInfo" style = "display: none;">
      <div class = "jumbotron">
        <h1>CompanyName</h1>
      </div>
    </div>
  </div>
</div>
</body>

<script>
function showCatalogue(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "block";
  driver.style.display = "none";
  company.style.display = "none";
}
function showDriver(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "block";
  company.style.display = "none";
}
function showCompany(){
  var catalog = document.getElementById("CatalogInfo");
  var driver = document.getElementById("DriverContent");
  var company = document.getElementById("CompanyInfo");
  catalog.style.display = "none";
  driver.style.display = "none";
  company.style.display = "block";
}
</script>
