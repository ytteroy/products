<?php
include 'init.php';

$page->set_page_title('New product');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $page->get_page_title() . ' - ' . PAGE_TITLE; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="bg-light">
    <form method="post" class="container">
		<div class="py-5 d-flex justify-content-between">
			<h2 class="mb-0">Product Add</h2>
			<input type="submit" name="add" class="btn btn-primary" value="Add" />
		</div>
		
<?php
if(isset($add)){
	if($add == 'checkform'){
		echo '<div class="alert alert-danger mb-4">Not all fields are filled!</div>';
	}else if($add == 'dberror'){
		echo '<div class="alert alert-danger mb-4">Database error, please try later.</div>';
	}else{
		echo '<div class="alert alert-success mb-4">Unexpected error!</div>';
	}
}
?>
		
		<div class="row">
			<div class="col-12 col-md-6">
				<div class="form-group row">
					<label for="code" class="col-sm-3 col-form-label">Code</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="code" id="code" required />
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-sm-3 col-form-label">Product name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="name" id="name" required />
					</div>
				</div>
				<div class="form-group row">
					<label for="price" class="col-sm-3 col-form-label">Price</label>
					<div class="col-sm-9">
						<input type="number" class="form-control" name="price" id="price" step="0.01" required />
					</div>
				</div>
			</div>
		</div>
	</form>
</body>
</html>