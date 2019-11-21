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

$items = ninja_catalog_items($cid);
$catname = ninja_catalog_name($cid);

$visible = ninja_catalog_visible($cid);
if ($_SERVER["REQUEST_METHOD"] == "POST")  // toggle visibility
{
	if ($_POST['Visible'] == 'on')
		$visible = 1;
	else
		$visible = 0;
	ninja_set_catalog_visible($cid, $visible);
}

?>

<html>
<?php include"htmlhead.php" ?>
<body>
<div class = "jumbotron" style = "margin:0;"> 
  <h1><?php echo $catname; ?></h1>
</div>
<table class = "table">
<thead>
   <th>Item Number</th>
   <th>Image</th>
   <th>Item Name</th>
   <th>Item Point Value</th>
   <th>Edit Item</th>
   <th>Remove Item</td>
</thead>
<?php

$rank = 1;
while ($row = $items->fetch_assoc())
{
	echo '
<tr>
	<td>'.$rank.'</td>
	<td>
	<img src = "data:image/png;base64,'.base64_encode($row['Image']).'" width = " 80px"/>
	</td>
	<td>'.$row['Name'].'</td>
	<td>'.$row['Price'].'</td>
	<td><a href = "edit_item.php?ItemID='.$row['ItemID'].'&CatalogID='.$cid.'">Edit</a></td>
	<td><a href = "remove_catalog_item.php?ItemID='.$row['ItemID'].'&CatalogID='.$cid.'">Remove</a></td>
</tr>
	';
	
}

?>
</table><div style = "text-align: center;">
<button class = "btn btn-primary" onclick = "location.href = 'base_catalog.php?CatalogID=<?php echo $cid; ?>' ">Add New Item</button>
<button class = "btn btn-primary" onclick = "location.href = 'logon.php' ">Back to Home</button></div>
			<form action = "rename_catalog.php">
			<input type = "text" name = "CatalogName">
			<input type = "hidden" name = "CatalogID" value = "<?php echo $cid; ?>">
			<input type = "submit" value = "Rename Catalog">
			</form>
	<form method = "post">
	<input type = "checkbox" name = "Visible" id = "Visible" onchange = "this.form.submit()" <?php if ($visible) echo 'checked'; ?>>
	<label class = "form-check-label" for = "Visible">Visible to Drivers</label>
	</form>
</body>
</html>
