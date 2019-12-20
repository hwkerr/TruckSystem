<?php

include "../db_ninja.php";

$cid = $_GET['cid'] ?? '';

$itemlist = ninja_browse_catalog_items($cid);

$itemjson = '{"Items": [';
$first = true;
while ($row = $itemlist->fetch_assoc())
{
	if (!$first)
	{
		$itemjson = $itemjson.',';
	}
	$itemjson = $itemjson.'{';	
	$itemjson = $itemjson.'"Name": "'.$row['Name'].'",';
	//$itemjson = $itemjson.'"Image": "'.$row['Image'].'",';
	$itemjson = $itemjson.'"Price": "'.$row['Price'].'",';
	$itemjson = $itemjson.'"ItemID": "'.$row['ItemID'].'",';
	$itemjson = $itemjson.'"CatalogID": "'.$row['CatalogID'].'",';
	$itemjson = $itemjson.'"WebSource": "'.$row['WebSource'].'",';
	$itemjson = $itemjson.'"LinkInfo": "'.$row['LinkInfo'].'"';
	$itemjson = $itemjson.'}';
	$first = false;
}
$itemjson = $itemjson.']}';

echo $itemjson;

?>
