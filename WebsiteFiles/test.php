<?php

include "db_ninja.php";
$res = ninja_point_gains("testdriver", 'testcompany');
while ($row = $res->fetch_assoc())
	echo $row['Timestamp']->format('Y-m-d');

?>
