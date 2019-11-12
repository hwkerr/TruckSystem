<html>
<?php include "htmlhead.php"?>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">

<div class = "container" style = "margin: 0 auto;">
	<div class = "row justify-content-center">
		<div class = "col-lg-6" style = "color: white;">
			<br><br><br>
			<div class = "form-group" style = "margin-center: auto;">
				<h1 style = "text-align: center;">Edit Item</h1>
				<label for = "newCatalog">Catalog</label>
				<input class = "form-control" id = "newCatalog" placeholder = "Current Catalog"/><br>
				<label for = "newName">Name</label>
				<input class = "form-control" id = "newName" placeholder = "Current Item Name" /><br>
				<label for = "newValue">Value</label>
				<input class = "form-control" id = "newValue" placeholder = "Current Point Value"/><br>
				<label for = "newQuantity">Amount</label>
				<input class = "form-control" id = "newQuantity" placeholder = "Current Item Quantity"/><br>
				<div style = "text-align: center;">
					<button class = "btn btn-outline-light" onclick = "location.href = 'catalog_view.php'">Return to Catalog</button>
					<button class = "btn btn-outline-light" onclick = "location.href = 'catalog_view.php'">Submit Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
