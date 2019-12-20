<?php

  include "../inc/dbinfo.inc";
  $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
  
	session_start();
	
	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true)
	{
		header("location: DesktopSite.php");
		exit;
	}

	$uid = $_SESSION['UserID'];

  $sql = "SELECT Image FROM User WHERE UserID=$uid";
  $result = $db->query("$sql");
  $row = mysql_fetch_assoc($result);
  mysql_close($db);

  header("Content-type: image/jpg");
  echo $row['Image'];
?>
