<?php 
	include "db_ninja.php";
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] === 'Driver')
	{
		header("location: DesktopSite.html");
		exit;
	}

	$catalog = $_GET['CatalogID'];
	$item = $_GET['ItemID'];

	$fileName = basename($_FILES["itemfile"]["name"]);
	$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
	{
		$allowTypes = array('jpg','png','jpeg','gif');
    		if(in_array(strtolower($fileType), $allowTypes))
		{
			$tempfile = $_FILES['itemfile']['tmp_name'];
			$img = addslashes(file_get_contents($tempfile));
			ninja_update_catalog_item_image($item, $catalog, $img);
		}
	}

	header('location: edit_item.php?ItemID='.$item.'&CatalogID='.$catalog);

?>
