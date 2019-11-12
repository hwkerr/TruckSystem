<?php 
	include "db_ninja.php";
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] != 'Sponsor')
	{
		header("location: DesktopSite.html");
		exit;
	}

	$fileName = basename($_FILES["file"]["name"]);
	$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
	{
		$allowTypes = array('jpg','png','jpeg','gif');
    		if(in_array(strtolower($fileType), $allowTypes))
		{
			$uid = $_SESSION['UserID'];
			$cid = ninja_sponsor_company_id($uid);
			$tempfile = $_FILES['file']['tmp_name'];
			$img = addslashes(file_get_contents($tempfile));
			ninja_update_company_image($cid, $img);
		}
	}

	header('location: edit_company.php');

?>
