<?php

include 'db_ninja.php';
session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: DesktopSite.php");
	exit;
}

$iid = $_GET['ItemID'];
$catid = $_GET['CatalogID'];
$catalog = ninja_catalog_name($catid);

if ($_SESSION['UserType'] === 'Sponsor')  // check if sponsor belongs to same company as catalog
{
	$ccid = ninja_catalog_company_id($catid);
	$uid = $_SESSION['UserID'];
	$scid = ninja_sponsor_company_id($uid);
	if ($ccid != $scid)
	{
		header("location: logon.php");
		exit;
	}
}


if ($_SERVER["REQUEST_METHOD"] == "POST")  // write changes
{
	$iname = $_POST['Name'];
	$iprice = $_POST['Price'];
	$idesc = $_POST['Description'];
	ninja_update_catalog_item($iid, $catid, $iname, $iprice, $idesc);
	header("location: catalog_view.php?CatalogID=".$catid);
	exit;
}
else
{
	$iname = ninja_item_name($iid, $catid);
	$iprice = ninja_item_price($iid, $catid);
	$idesc = ninja_item_description($iid, $catid);
}

?>

<html>
<?php include "htmlhead.php"?>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">

<div class = "container" style = "margin: 0 auto;">
	<div class = "row justify-content-center">
		<div class = "col-lg-6" style = "color: white;">
			<br><br><br>
				<h1 style = "text-align: center;">Edit <?php echo $iname; ?></h1>
				<h2 style = "text-align: center;">Catalog <?php echo $catalog; ?></h2>
        		<br>
			<form action="upload_iimg.php?CatalogID=<?php echo $catid; ?>&ItemID=<?php echo $iid; ?>" method="post" enctype="multipart/form-data">
        		  Select Image File to Upload:
        		  <input type="file" name="itemfile">
        		  <input type="submit" value="Upload">
        		</form>
        		<form action = "url_iimg.php?CatalogID=<?php echo $catid; ?>&ItemID=<?php echo $iid; ?>" method = "post">
        		  <input type = "text" name="text">
        		  <input type = "submit" value="Upload URL">
        		</form>
          	 	<form style = "margin: 0 auto;" method = "post">
			<div class = "form-group" style = "margin-center: auto;">
				<label for = "newName">Name</label>
				<input class = "form-control" id = "newName" name = "Name" placeholder = "Current Item Name" value = <?php echo '"'.$iname.'"'; ?> /><br>
				<label for = "newValue">Value</label>
				<input class = "form-control" id = "newValue" name = "Price" placeholder = "Current Point Value" value = <?php echo '"'.$iprice.'"'; ?> /><br>
				<label for = "newDesc">Description</label>
				<input class = "form-control" id = "newDesc" name = "Description" placeholder = "Current Item Description" value = <?php echo '"'.$idesc.'"'; ?> /><br>
				<div style = "text-align: center;">
					<button class = "btn btn-outline-light" onclick = "location.href = 'catalog_view.php?CatalogID=<?php echo $catid; ?>'">Return to Catalog</button>
					<button class = "btn btn-outline-light" type = "submit">Submit Changes</button>
				</div>
			</div>
		 	</form>
		</div>
	</div>
</div>
</body>
</html>
