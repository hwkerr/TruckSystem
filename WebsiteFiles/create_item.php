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
	$websource = $_POST['WebSource'];
	$linkinfo = $_POST['LinkInfo'];
	ninja_add_base_item($websource, $linkinfo);
	header("location: base_catalog.php?CatalogID=".$cid);
}

?>

<html>
<?php include "htmlhead.php"?>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">

<div class = "container" style = "margin: 0 auto;">
	<div class = "row justify-content-center">
		<div class = "col-lg-6" style = "color: white;">
			<br><br><br>
			<div class = "form-group" style = "margin-center: auto;">
				<h1 style = "text-align: center;">Create New Item</h1>
				<form method = "post">
				<label for = "WebSource">Web Source</label>
				<input class = "form-control" id = "WebSource" placeholder = "Web Source" name = "WebSource"/><br>
				<label for = "LinkInfo">Link Info</label>
				<input class = "form-control" id = "LinkInfo" placeholder = "Link Info" name = "LinkInfo"/><br>
				<div style = "text-align: center;">
					<button class = "btn btn-outline-light" type = "submit" >Create Item</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
