<?php 
	include "../inc/dbinfo.inc";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
	{
		header("location: logon.php");
		exit;
	}

	$fileName = basename($_POST["URL"]);
	$fileType = pathinfo($fileName,PATHINFO_EXTENSION);

	if (isset($_POST["submit"]) && !empty($_FILES["file"]["name"]))
	{
		$allowTypes = array('jpg','png','jpeg','gif','pdf');
    		if(in_array($fileType, $allowTypes))
		{
			$img = file_get_contents($fileName);
			$uid = $_Session['UserID'];
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

	header("location: logon.php");
?>
