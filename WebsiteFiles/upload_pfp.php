<?php 
	include "../inc/dbinfo.inc";
	include "resize_image.php";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
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
			$tempfile = $_FILES['file']['tmp_name'];
			$img = addslashes(file_get_contents($tempfile));
            		$insert = $db->query("UPDATE Account SET Image = '$img' WHERE UserID = '$uid'");
			if($insert)
			{
            			$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
				header("location: logon.php");
           		}
			else
			{
           	    		$statusMsg = "File upload failed, please try again.";
            		} 
		}
	}

?>
