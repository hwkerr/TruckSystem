<?php

include "db_ninja.php";

		$entry = 1;
		$res = ninja_companies();
		while ($row = $res->fetch_assoc())
		{
			echo '<tr>';
			echo '<th>'.$entry.'</th>';
			$cid = $row['CompanyID'];
			echo '<td>'.$cid.'</td>';
			echo '<td>'.ninja_company_name($cid).'</td>';
			echo '<td>'.ninja_company_sponsor_count($cid).'</td>';
			echo '<td>'.ninja_company_driver_count($cid).'</td>';
			echo '</tr>';
			$entry = $entry + 1;
		}
?>
