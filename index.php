<?php
include 'init.php';

$page->set_page_title('List products');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $page->get_page_title() . ' - ' . PAGE_TITLE; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	
	<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.full.min.css">
	<script src="https://cdn.jsdelivr.net/npm/handsontable@latest/dist/handsontable.full.min.js"></script>
</head>
<body class="bg-light">
    <div class="container">
		<div class="py-5 d-flex justify-content-between">
			<h2 class="mb-0"><?php echo $page->get_page_title(); ?></h2>
			<div class="btn-group" role="group" aria-label="Basic example">
				<a href="<?php echo SITE_URL; ?>/ajax/exporttoexcel.php" class="btn btn-info ml-2">Export to Excel</a>
				<a href="<?php echo SITE_URL; ?>/add.php" class="btn btn-success ml-2">Add product</a>
			</div>
		</div>
		
		<div class="card mb-4">
			<div class="card-header"><h4 class="card-title mb-0">Import Excel file</h4></div>
			<form method="post" class="card-body" enctype="multipart/form-data">
				<input type="file" name="excelfile" class="form-control" />
				<input type="submit" name="importexcel" class="btn btn-primary mt-3" value="Upload" />
			</form>
		</div>
		
		<div id="productsTable"></div>
		
	</div>
	
<script>
var dataObject = <?php echo json_encode($products->list()); ?>;

var hotElement = document.querySelector('#productsTable');
var hotElementContainer = hotElement.parentNode;
var hotSettings = {
	licenseKey: 'non-commercial-and-evaluation',
	data: dataObject,
	columns: [
		{
			data: 'id',
			type: 'numeric',
			width: 15,
			editor: false
		},
		{
			data: 'sku',
			type: 'text'
		},
		{
			data: 'name',
			type: 'text'
		},
		{
			data: 'price',
			type: 'numeric',
			numericFormat: {
				pattern: '0,0.00'
			},
			width: 40,
		},{
			data: 'remove',
			renderer: 'html',
			editor: false,
			width: 40,
		},
	],
	stretchH: 'all',
	colHeaders: [
		'ID',
		'SKU',
		'Name',
		'Price (EUR)',
		'Remove',
	],
	columnSorting: {
		indicator: true
	},
	autoColumnSize: {
		samplingRatio: 23
	},
	
	afterChange: function (changes, source) {
		if (source === 'loadData') {
		  return; //don't save this change
		}
		
		changes && changes.forEach(change => {
			const [row, col, prevVal, nextVal] = change;
			
			var rowid = dataObject[row]['id'];
			$.post('<?php echo $siteurl; ?>/ajax/updatevalue.php', {id:rowid,col,nextVal}, function(data){
				console.log(data);
			});
		})
	},
};

var productsTable = new Handsontable(hotElement, hotSettings);
</script>
</body>
</html>