<?php

include "db_ninja.php";

session_start();

if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
{
	header("location: DesktopSite.php");
	exit;
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

$cid = $_GET['CatalogID'];

$uid = $_SESSION['UserID'];
$pfp = ninja_pfp($uid);
$fname = ninja_fname($uid);
$lname = ninja_lname($uid);

if ($_SESSION['UserType'] === 'Sponsor')  // check if sponsor belongs to same company as catalog
{
	$ccid = ninja_catalog_company_id($cid);
	$uid = $_SESSION['UserID'];
	$pfp = ninja_pfp($uid);
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
	if (!$img)  // try pulling image from site if none was uploaded
	{
		$site = $_POST['WebSource'];
		if ($site == 'Ebay')
		{
			$link = $_POST['LinkInfo'];
			$html = file_get_contents($link);
			if ($html)
				$img = addslashes(file_get_contents(get_string_between($html, 'itemprop="image" src="', '"')));
		}
	}
	ninja_add_catalog_item($iid, $cid, $name, $price, $desc, $img);
}

$items = ninja_browse_base_items($cid);

?>

<?php include "htmlhead.php";?>

<html style = "height: 100%">
<?php
if ($_SESSION['UserType'] === "Sponsor")
 include "sponsor_header.php";
else if ($_SESSION['UserType'] === "Admin")
 include "admin_header.php";
?>
<div class = "jumbotron">
	<h1>Base Catalog</h1>
</div>
<div class = "container">
<!--Item Div-->

<?php

	$num = 1;
	$rank = 0;
	while ($row = $items->fetch_assoc())
	{
		$site = $row['WebSource'];
		$link = $row['LinkInfo'];
		$biid = $row['ItemID'];
		$name = 'Error fetching info (Item ID '.$biid.')';
		$imgsrc = "Assets/DefaultPicture.jpg";
		
		if ($site == 'Ebay')
		{
			try
			{
				$html = file_get_contents($link);
				if ($html)
				{
					$name = get_string_between($html, '<h1 class="it-ttl" itemprop="name" id="itemTitle"><span class="g-hdn">Details about  &nbsp;</span>', '</h1>');
					$imgsrc = get_string_between($html, 'itemprop="image" src="', '"');
				}
			} 
			catch (Exception $e){}
		}
		else if ($site == 'Amazon')
		{
			continue;  // TODO
		}
		else
		{
			continue;
		}
		 
		if($rank % 4 == 0){
			echo "<div class = 'row'>";
		}

		echo ' 
		<div class = "col-md-3 card-deck">
		<div class = "card">
			<img width = "300px" class = "card-img-top" src = "'.$imgsrc.'">
			<div class = "card-body">
				<h5>'.$name.'</h5>
			</div>

			<div class = "card-footer">
				';
					
					if($_SESSION['UserType'] == 'Admin')
						if(isset($_GET['CatalogID']))
						{
							echo '<form><button type = "button" class = "btn btn-primary" data-toggle = "modal" data-target = "#modalName'.$num.'">Add item</button></form>';
							echo '<button class = "btn btn-danger" onclick="location.href = \'delete_base_item.php?ItemID='.$biid.'&CatalogID='.$cid.'\';">Delete</button>';
						}
						else
							echo '<button class = "btn btn-danger" onclick="location.href = \'delete_base_item.php?ItemID='.$biid.'\';">Delete</button>';
					else if($_SESSION['UserType'] == 'Sponsor'){
						echo '<form><button type = "button" class = "btn btn-primary" data-toggle = "modal" data-target = "#modalName'.$num.'">Add item</button></form>';
					}

		echo '		
				
			</div>
		</div>
		<div class = "modal fade" id = "modalName'.$num.'">
			<div class = "modal-dialog modal-dialog-centered" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						Add Item
					</div>	
					<div class = "modal-body">
						<form method = "post" enctype = "multipart/form-data">
							<div class = "form-group">
								<label for = "ItemName">Item Name</label>
								<input name = "ItemName" class = "form-control" type = "text" id = "ItemName" placeholder = "Item Name" value = "'.$name.'">
								<input name = "ItemID" class = "form-control" type = "hidden" id = "ItemID" value = "'.$biid.'">
								<input name = "LinkInfo" class = "form-control" type = "hidden" id = "LinkInfo" value = "'.$link.'">
								<input name = "WebSource" class = "form-control" type = "hidden" id = "WebSource" value = "'.$site.'">
							</div>
							<div class = "form-group">
								<label for = "Price">Price</label>
								<input name = "Price" class = "form-control" type = "text" id = "Price" placeholder = "Price">
							</div>
							<div class = "form-group">
								<label for = "Description">Description</label>
								<input name = "Description" class = "form-control" type = "text" id = "Description" placeholder = "Description">
							</div>
							<div class = "form-group">
								<label for = "ImageSelect">Select an image</label>
								<input name = "Image'.$row['ItemID'].'" type = "file" id = "ImageSelect" class = "form-control-file">
							</div>
					</div>
					<div class = "modal-footer">
						<button type = "button" class = "btn btn-seconday" data-dismiss = "modal">Close</button>
						<button type = "submit" class = "btn btn-primary">Add Item</button>
					</div>
						</form>
				</div>
			</div>
		</div>
		</div>
		';
	
	if(($rank + 1) % 4 == 0){
		
/*		echo '</div>';*/
		echo '</div><br>';
	}

	$rank = $rank + 1;
	$num = $num + 1;

	}
	if($rank % 4 != 0){
	/*	echo '</div>';*/
		echo '</div><br>';
	}
?>

</div>
<br>
<div style = "text-align: center;">
<?php
	if ($_SESSION['UserType'] == 'Sponsor')
	{
		echo '<button class = "btn btn-primary" style = "margin-right: 10px;" onclick = "location.href = \'create_item.php?CatalogID='.$cid.'\' ">Add New Base Item</button>';
		echo '<button class = "btn btn-primary" onclick = "location.href = \'catalog_view.php?CatalogID='.$cid.'\' ">Back to Catalog</button>';
	}
	else if ($_SESSION['UserType'] == 'Admin')
	{
		if (isset($_GET['CatalogID']))
			echo '<button class = "btn btn-primary" onclick = "location.href = \'catalog_view.php?CatalogID='.$cid.'\' ">Back to Catalog</button>';
		else
			echo '<button class = "btn btn-primary" onclick = "location.href = \'admin_view.php\' ">Back to Home</button>';
	}
?>
</div>
<br><br><br><br>
</html>
