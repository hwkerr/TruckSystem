<?php 
	include "db_ninja.php";
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] != 'Sponsor')
	{
		header("location: DesktopSite.php");
		exit;
	}

	$catalog = $_GET['CatalogID'];
	$item = $_GET['ItemID'];
	
	if (!empty($_POST["text"]))
	{
		$fileName = $_POST["text"];
		$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
		$allowTypes = array('jpg','png','jpeg','gif','pdf');
    		if(in_array(strtolower($fileType), $allowTypes))
		{
			$img = addslashes(file_get_contents($fileName));
            		ninja_update_catalog_item_image($item, $catalog, $img);
		}
	}

	header('location: edit_item.php?ItemID='.$item.'&CatalogID='.$catalog);
?>
