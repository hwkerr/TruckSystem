<?php
	include "../inc/dbinfo.inc";
	include "db_ninja.php";
	$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();

	if (!isset($_SESSION['Logged']) || $_SESSION['Logged'] !== true || $_SESSION['UserType'] !== 'Driver')
	{
		header("location: logon.php");
		exit;
	}

	$uid = $_SESSION['UserID'];

	$cid = ninja_current_driver_company($uid);
 ?>

<html style = "height: 100%;">
<?php include "htmlhead.php"?>
<body>
<?php include "driver_header.php"; ?>

      <div class = "jumbotron">
	<h1>Driver Store</h1>
	</div>
      <div class = "container" style = "width: 80%;" id = "Catalog">
            <br />
	<div id = "DefaultItemView" style = "display: inline-block;">

	<?php
		$items = ninja_browse_catalog_items($cid);
		$rank = 0;
		while ($row = $items->fetch_assoc())
		{
			$name = $row['Name'];
			$iid = $row['ItemID'];
			$catid = $row['CatalogID'];
			$iimg = $row['Image'];
			if ($rank % 4 == 0){
              			echo '<div class = "row">';
			}
			echo '<div class = "col-md-3 card-deck">';
              		echo 	'<div class="card">';
              		echo     '<img "card-img-top" style = "width: 100%;" src="data:image/png;base64,'.base64_encode($iimg).'">';
              		echo       '<div class="card-body">';
              		echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
              		echo         '<p class="card-text">'.$row['Price'].' points</p>';
              		echo       '</div>';
              		echo     '</div>';
			echo '</div>';
			if (($rank + 1) % 4 == 0){
				echo '</div><br>';
			}
			$rank += 1;
		}
		if ($rank % 4 != 0){
			echo '</div>';
		}
	?>
	</div>

<div id = "AscNameItemView" style = "display: none;">

        <?php
                $items = ninja_browse_catalog_items_asc_name($cid);
                $rank = 0;
                while ($row = $items->fetch_assoc())
                {
                        $name = $row['Name'];
                        $iid = $row['ItemID'];
                        $catid = $row['CatalogID'];
                        $iimg = $row['Image'];
                        if ($rank % 4 == 0){
                       		echo '<div class = "row">';
			}
                        	echo '<div class = "col-md-3 card-deck">';
                        echo    '<div class="card" style="width: 18rem;">';
                        echo     '<img "card-img-top" style = "width: 100%;" src="data:image/png;base64,'.base64_encode($iimg).'">';
                        echo       '<div class="card-body">';
                        echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
                        echo         '<p class="card-text">'.$row['Price'].' points</p>';
                        echo       '</div>';
                        echo     '</div>';
			echo '</div>';
                        if (($rank + 1) % 4 == 0){
                                echo   '</div><br>';
                        }
                        $rank += 1;
                }
                if ($rank % 4 != 0){
			echo '</div>';
		}
        ?>
       </div>
		

<div id = "DescNameItemView" style = "display: none;">
        <?php
                $items = ninja_browse_catalog_items_desc_name($cid);
                $rank = 0;
                while ($row = $items->fetch_assoc())
                {
                        $name = $row['Name'];
                        $iid = $row['ItemID'];
                        $catid = $row['CatalogID'];
                        $iimg = $row['Image'];
                        if ($rank % 4 == 0){
                        	echo '<div class = "row">';
			}
               	  	echo '<div class = "col-md-3 card-deck">';
                        echo    '<div class="card" style="width: 18rem;">';
                        echo     '<img "card-img-top" style = "width: 100%;" src="data:image/png;base64,'.base64_encode($iimg).'">';
                        echo       '<div class="card-body">';
                        echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
                        echo         '<p class="card-text">'.$row['Price'].' points</p>';
                        echo       '</div>';
                        echo     '</div>';
			echo '</div>';
                        if (($rank + 1) % 4 == 0){
				echo   '</div><br>';
                        }
                        $rank += 1;
                }
                if ($rank % 4 != 0){
			echo '</div>';
		}
        ?>
        </div>

<div id = "AscPriceItemView" style = "display: none;">

        <?php
                $items = ninja_browse_catalog_items_asc_price($cid);
                $rank = 0;
                while ($row = $items->fetch_assoc())
                {
                        $name = $row['Name'];
                        $iid = $row['ItemID'];
                        $catid = $row['CatalogID'];
                        $iimg = $row['Image'];
                        if ($rank % 4 == 0){
                        	echo '<div class = "row">';
			}
                        echo '<div class = "col-md-3 card-deck">';
                        echo    '<div class="card" style="width: 18rem;">';
                        echo     '<img "card-img-top" style = "width: 100%;" src="data:image/png;base64,'.base64_encode($iimg).'">';
                        echo       '<div class="card-body">';
                        echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
                        echo         '<p class="card-text">'.$row['Price'].' points</p>';
                        echo       '</div>';
                        echo     '</div>';
			echo '</div>';
                        if (($rank + 1) % 4 == 0){
                                echo   '</div><br>';
                        }
                        $rank += 1;
                }
                if ($rank % 4 != 0){
                        echo '</div>';
		}
        ?>
        </div>

<div id = "DescPriceItemView" style = "display: none;">

        <?php
                $items = ninja_browse_catalog_items_desc_price($cid);
                $rank = 0;
                while ($row = $items->fetch_assoc())
                {
                        $name = $row['Name'];
                        $iid = $row['ItemID'];
                        $catid = $row['CatalogID'];
                        $iimg = $row['Image'];
                        if ($rank % 4 == 0){
                        	echo '<div class = "row">';
			}
                        echo '<div class = "col-md-3 card-deck">';
                        echo    '<div class="card" style="width: 18rem;">';
                        echo     '<img "card-img-top" style = "width: 100%;" src="data:image/png;base64,'.base64_encode($iimg).'">';
                        echo       '<div class="card-body">';
                        echo         '<a href = "driver_item_view.php?ItemID='.$iid.'&CatalogID='.$catid.'"><h5 class="card-title">'.$name.'</h5></a>';
                        echo         '<p class="card-text">'.$row['Price'].' points</p>';
                        echo       '</div>';
                        echo     '</div>';
			echo '</div>';
                        if (($rank + 1) % 4 == 0){
				echo   '</div><br>';
                        }
                        $rank += 1;
                }
                if ($rank % 4 != 0){
			echo '</div>';
		}
        ?>
        </div>


<script>
function sortItems(val){
        var defaultview = document.getElementById("DefaultItemView");
        var ascName = document.getElementById("AscNameItemView");
        var descName = document.getElementById("DescNameItemView");
        var ascPrice = document.getElementById("AscPriceItemView");
        var descPrice = document.getElementById("DescPriceItemView");
        if(val == 1){
                defaultview.style.display = "none";
                ascName.style.display = "block";
                descName.style.display = "none";
                ascPrice.style.display = "none";
                descPrice.style.display = "none";
        }
        else if(val == 2){
                defaultview.style.display = "none";
                ascName.style.display = "none";
                descName.style.display = "block";
                ascPrice.style.display = "none";
                descPrice.style.display = "none";

        }
        else if(val == 3){
                defaultview.style.display = "none";
                ascName.style.display = "none";
                descName.style.display = "none";
                ascPrice.style.display = "block";
                descPrice.style.display = "none";

        }
        else{
                defaultview.style.display = "none";
                ascName.style.display = "none";
                descName.style.display = "none";
                ascPrice.style.display = "none";
                descPrice.style.display = "block";
        }
}
</script>
<br><br><br><br><br><br><br><br><br><br><br>
 <div class = "card" id = "sortSelect" style = "position:fixed; bottom: 20; left: 20; z-index:1;">
                        <div class = "card-header">
                                Sort
                        </div>
                        <div class = "card-body">
                                <div class= "input-group">
                                        <select class="custom-select" id="inlineFormCustomSelectPref">
                                                <option selected>Select sort</option>
                                                <option value = "1">Alphabetical Sort(A-Z)</option>
                                                <option value = "2">Alphabetical Sort(Z-A)</option>
                                                <option value = "3">Price Sort(Low-High)</option>
                                                <option value = "4">Price Sort(High-Low)</option>
                                        </select>
                                        <button class = "btn btn-primary" onClick = "sortItems(getElementById('inlineFormCustomSelectPref').value)">Sort</button>
                                </div>
                        </div>
                </div>

              <br />
          </div>
</body>
