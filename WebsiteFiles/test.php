<?php

include "db_ninja.php";

$items = ninja_orders('testdriver', 'testcompany');

while ($row = $items->fetch_assoc())
{
	echo $row['Timestamp']." \n";
}

?>
