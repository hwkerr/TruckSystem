<?php 
	include "db_ninja.php";
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] != 'Sponsor')
	{
		header("location: logon.php");
		exit;
	}

	if (!empty($_POST["text"]))
	{
		$fileName = $_POST["text"];
		$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
		$allowTypes = array('jpg','png','jpeg','gif','pdf');
    		if(in_array(strtolower($fileType), $allowTypes))
		{
			$img = addslashes(file_get_contents($fileName));
			$uid = $_SESSION['UserID'];
			$cid = ninja_sponsor_company_id($uid);
            		ninja_update_company_image($cid, $img);
		}
	}

	header('location: edit_company.php');
?>
