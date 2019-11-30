<?php

include "../db_ninja.php";

$count = $_GET['count'];
$did = $_GET['did'];

if ($count == 'oops')
	return;

$current = 1;

$orders = array();

while ($current <= $count)
{
	$itemid = $_GET['itemid'.$current];
	$catid = $_GET['catid'.$current];

	$itemcount = $_GET['quantity'.$current];
	$currentitem = 1;

	while ($currentitem <= $itemcount)
	{
		$order = array();
		$order['ItemID'] = $itemid;
		$order['CatalogID'] = $catid;
		$order['Price'] = ninja_item_price($itemid, $catid);
		$orders[] = $order;
		$currentitem += 1;
	}
		
	$current += 1;
}

echo ninja_place_order($did, $orders);

?>
