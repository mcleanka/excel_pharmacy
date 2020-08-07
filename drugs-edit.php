<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "drugs.php";
require_once 'Includes/templates/header.php';

$id  = escape($_GET['id']);
$qry = "SELECT * FROM tbl_drug WHERE drug_id=$id";
$res = $db->query($qry) or die($qry);
if($res->num_rows == 0){
	die("Item not found");
}

while ($row = mysqli_fetch_assoc($res)) {
	$name 	= $row['name'];
	$code 	= $row['code'];
	$price 	= $row['price'];
}
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

		$qry = "UPDATE tbl_drug SET name='$name', code='$code', price='$price' WHERE drug_id=$id";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Product Edited Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='drugs.php';
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
    <label class="header-name">Edit Product</label>
    <span class="pull-right pr-3">
		<?php include 'Includes/components/drugs-top-buttons.php'; ?>
    </span>
    <hr>
</div>

<div class="row">
	<div class="col-xs-6">
		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" action="drugs-edit.php?id=<?php echo $id; ?>" method="post">
			<div class="form-group">
				<label for="name">Product Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" value="<?php if(isset($name)){ echo $name; } ?>">
			</div>

			<div class="form-group">
				<label for="code">Product Code</label>
				<input type="text" class="form-control" id="code" name="code" placeholder="Enter Product Code" value="<?php if(isset($code)){ echo $code; } ?>">
			</div>

			<div class="form-group">
				<label for="price">Unit Price [ MWK ]</label>
				<input type="text" class="form-control" id="price" name="price" placeholder="Enter Unit Price" value="<?php if(isset($price)){ echo $price; } ?>">
			</div>
		<button type="submit" name="submit" class="btn btn-primary">Save Info</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
