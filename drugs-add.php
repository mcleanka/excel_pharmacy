<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "drugs.php";
require_once 'Includes/templates/header.php';

if (isset($_POST['submit'])) {
	$errors = "";
	if(empty($_POST['name'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The drug name field is required.</div>";
	}
	if(empty($_POST['code'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The drug code field is required.</div>";
	}
	if(empty($_POST['price'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The unit price field is required.</div>";
	}
	if(!is_numeric($_POST['price'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Invalid unit price format</div>";
	}

	if($errors==""){
		$name 	= $_POST['name'];
		$code 	= $_POST['code'];
		$price 	= $_POST['price'];
		// $ttype 	= $_POST['tariff'];

		$qry = "INSERT INTO tbl_drug (name,price,code,state) VALUES ('$name', '$price', '$code','active');";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Product Added Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='drugs-add.php';
				});
		</script>";	
	}
}
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="container p-fixed" id="main-content">
<div class="header">
    <label class="header-name">Add New Product</label>
    <span class="pull-right pr-3">
          <a href="drugs.php" class="btn btn-primary">Product List</a>
        <a href="drugs-csv.php" class="btn btn-primary">Excel Upload</a>
    </span>
    <hr>
</div>

<div class="row">
	<div class="col-xs-6">
		<?php 
			if(!isset($_POST['submit'])):
		?>
		
		<div class="alert alert-info">Please Complete the Form Below</div>
		
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" action="drugs-add.php" method="post">
			<div class="form-group">
				<label for="name">Product Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>">
			</div>

			<div class="form-group">
				<label for="code">Product Code</label>
				<input type="text" class="form-control" id="code" name="code" placeholder="Enter Product Code" value="<?php if(isset($_POST['code'])){ echo $_POST['code']; } ?>">
			</div>

			<div class="form-group">
				<label for="price">Unit Price [ MWK ]</label>
				<input type="text" class="form-control" id="price" name="price" placeholder="Enter Unit Price" value="<?php if(isset($_POST['price'])){ echo $_POST['price']; } ?>">
			</div>		
		<button type="submit" name="submit" class="btn btn-primary">Add Product</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
