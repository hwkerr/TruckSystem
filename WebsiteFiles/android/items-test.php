<?php

include "../db_ninja.php";

$cid = $_GET['cid'] ?? 'testcompany';

$itemlist = ninja_browse_catalog_items($cid)->fetch_assoc();

echo json_encode($itemlist);

/*$realitemlist = array();
while ($row = $itemlist->fetch_assoc())
{	
	echo json_encode($row);
	//$realitemlist[] = json_encode($row);
}*/

//header('Content-type: text/javascript');

//echo json_encode($realitemlist, JSON_PRETTY_PRINT);
//echo json_encode($realitemlist);

?>
