<?php

include "db_ninja.php";


$iid = '0a382137e5309e76';

	$db = dojo_connect();
	$pst = $db->prepare("SELECT WebSource, LinkInfo FROM CatalogItem WHERE ItemID = ?");
	$pst->bind_param("s", $iid);
	$pst->execute();
	$res = $pst->get_result();
	if ($row = $res->fetch_assoc())
	{
		$site = $row['WebSource'];
		$link = $row['LinkInfo'];
		if ($site == 'Ebay')
		{
			$html = file_get_contents($link);
			if ($html)
			{
				$price = dojo_get_string_between($html, 'US $', '<');
				echo floatval($price);
			}
			else echo 0.3;
		}
		else echo 0.2;  // TODO other websites
	}
	else echo 0.1;

?>
