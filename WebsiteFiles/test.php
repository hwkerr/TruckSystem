<?php

include "db_ninja.php";
$companies = ninja_driver_company_list('testdriver');
			echo "<p id = 'PhoneNumber'>";
			echo "Phone: ".$phone;
			echo "</p>";
			echo "<p id = 'Address'>";
			echo "Address: ".$address;
			echo "</p>";
			echo "<p id = 'CurrentCompany'>";
			echo "Current Company: ".$company;
			echo "</p>";

                      	echo '<div class = row>';
                        echo '<div class = "col">';
                        echo '<div class = "form-group">';
                        echo  '<label for = "DriverorSponsor"></label>';
                        echo  '<select name = "DriverSponsor" class = "form-control" id = "DriverorSponsor">';
			$companies->data_seek(0);
			while ($row = $companies->fetch_assoc())
			{
				echo '<option value = "'.$row['CID'].'">';
				echo $row['CName'];
				echo '</option>';
			}
                        echo  '</select>';
			echo '</div>';
			echo '</div>';
			echo '</div>';

			echo "<p id = 'SpentPoints'>";
			echo "Total Points Spent: ".$spent;
			echo "</p>";
			echo "<p id = 'EarnedPoints'>";
			echo "Total Points Earned: ".$earned;
			echo "</p>";
			echo "<p id = 'DriverSpace'>";
			echo "";
			echo "</p>";

?>
