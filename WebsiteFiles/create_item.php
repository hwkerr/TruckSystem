<html>
<?php include "htmlhead.php"?>
<body style = "height: 100%; background-image: linear-gradient(to bottom right, #071461, #0B358E);">

<div class = "container" style = "margin: 0 auto;">
	<div class = "row justify-content-center">
		<div class = "col-lg-6" style = "color: white;">
			<br><br><br>
			<div class = "form-group" style = "margin-center: auto;">
				<h1 style = "text-align: center;">Create New Item</h1>
				<label for = "Catalog">Catalog</label>
				<input class = "form-control" id = "Catalog" placeholder = "Catalog Name"/><br>
				<label for = "Name">Name</label>
				<input class = "form-control" id = "Name" placeholder = "Item Name" /><br>
				<label for = "Value">Value</label>
				<input class = "form-control" id = "Value" placeholder = "Point Value"/><br>
				<label for = "Quantity">Amount</label>
				<input class = "form-control" id = "Quantity" placeholder = "Item Quantity"/><br>
				<div style = "text-align: center;">
					<button class = "btn btn-outline-light" onclick = "location.href = 'catalog_view.php'">Return to Catalog</button>
					<button class = "btn btn-outline-light" onclick = "location.href = 'catalog_view.php'">Create New Item</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
