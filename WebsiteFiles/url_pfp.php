<?php 
	include "../inc/dbinfo.inc";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
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
            		$insert = $db->query("UPDATE Account SET Image = '$img' WHERE UserID = '$uid'");
			if($insert)
			{
            			$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
			}
			else
			{
           	    		$statusMsg = "File upload failed, please try again.";
            		} 
		}
	}

	header("location: edit_profile.php");
?>
