<?php

include "db_ninja.php";
if (isset($_SESSION['Cart']))
	echo $_SESSION['Cart'];
else
	echo 'not even set smh';

ninja_catalogs('testcompany');

echo ninja_catalog_item_count('testcatalog');

?>
