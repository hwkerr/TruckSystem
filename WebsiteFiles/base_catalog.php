<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: logon.php");
	exit;
}

$cid = $_GET['CatalogID'];

if ($_SESSION['UserType'] === 'Sponsor')  // check if sponsor belongs to same company as catalog
{
	$ccid = ninja_catalog_company_id($cid);
	$uid = $_SESSION['UserID'];
	$scid = ninja_sponsor_company_id($uid);
	if ($ccid != $scid)
	{
		header("location: logon.php");
		exit;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$iid = $_POST['ItemID'];
	$name = $_POST['ItemName'];
	$price = $_POST['Price'];
	$desc = $_POST['Description'];
	$img = '';
	$fileName = basename($_FILES["Image".$iid]["name"]);
	$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
	$allowTypes = array('jpg','png','jpeg','gif');
    	if(in_array(strtolower($fileType), $allowTypes))
	{
		$tempfile = $_FILES['Image'.$iid]['tmp_name'];
		$img = addslashes(file_get_contents($tempfile));
	}
	ninja_add_catalog_item($iid, $cid, $name, $price, $desc, $img);
}

$items = ninja_browse_base_items($cid);

?>

<?php include "htmlhead.php";?>

<html style = "height: 100%">
<div class = "jumbotron">
	<h1>Base Catalog</h1>
</div>
<div class = "container">
<!--Item Div-->
<div class = "row">
	<div class = "card-deck">


<?php
	$num = 1;
	while ($row = $items->fetch_assoc())
	{ 
		echo '
		<div class = "card">
			<img class = "card-img-top" src = "Assets/DefaultPicture.jpg">
			<div class = "card-body">
				<h5>'.$row['ItemID'].'</h5>
			</div>

			<div class = "card-footer">
				<form>
					<button type = "button" class = "btn btn-primary" data-toggle = "modal" data-target = "#modalName'.$num.'">Add item</button>
				</form>
				
			</div>
		</div>
		<div class = "modal fade" id = "modalName'.$num.'">


		<div class = "modal-content">
			<div class = "modal-header">
				Add Item
			</div>
				<form method = "post" enctype = "multipart/form-data">
			<div class = "modal-body">
				<div class = "form-inline">
					<input name = "ItemName" class = "form-control" type = "text" id = "ItemName" placeholder = "Item Name">
					<input name = "ItemID" class = "form-control" type = "hidden" id = "ItemID" placeholder = "Item ID" value = "'.$row['ItemID'].'">
					<label for = "ItemName">Item Name</label>
				</div>
				<div class = "form-inline">
					<input name = "Price" class = "form-control" type = "text" id = "Price" placeholder = "Price">
					<label for = "Price">Price</label>
				</div>
				<div class = "form-inline">
					<input name = "Description" class = "form-control" type = "text" id = "Description" placeholder = "Description">
				</div>

				<div class = "form-inline">
					<input name = "Image'.$row['ItemID'].'" type = "file" class = "form-control-file">

				</div>
			</div>
			<div class = "modal-footer">
				<button type = "button" class = "btn btn-seconday" data-dismiss = "modal">Close</button>
				<button type = "submit" class = "btn btn-primary">Add Item</button>
			</div>
				</form>

		</div>
		</div>
		';
	}
?>


	</div>
</div>



</div>

<div style = "text-align: center;">
<button class = "btn btn-primary" onclick = "location.href = 'create_item.php?CatalogID=<?php echo $cid; ?>' ">Add New Base Item</button>
<button class = "btn btn-primary" onclick = "location.href = 'catalog_view.php?CatalogID=<?php echo $cid; ?>' ">Back to Catalog</button></div>

</html>
