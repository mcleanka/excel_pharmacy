<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';

$id  = escape($_GET['id']);
$qry = "SELECT * FROM tbl_supplier WHERE supplier_id=$id";
$res = $db->query($qry) or die($qry);
if($res->num_rows == 0){
	die("Item not found");
}

while ($row = mysqli_fetch_assoc($res)) {
	$name 		= $row['name'];
	$phone 		= $row['phone_number'];
	$email 		= $row['email_address'];
	$country 	= $row['country'];
}
if (isset($_POST['submit'])) {
	$errors = "";
	if(empty($_POST['name'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The supplier name field is required.</div>";
	}
	if(empty($_POST['phone'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The supplier phone number field is required.</div>";
	}

	if($errors==""){
		$name 		= $_POST['name'];
		$phone 		= $_POST['phone'];
		$email 		= $_POST['email'];
		$country 	= $_POST['country'];

		$qry = "UPDATE tbl_supplier SET name='$name', phone_number='$phone', email_address='$email', country='$country' WHERE supplier_id=$id";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Supplier Edited Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='inventory-suppliers.php';
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
        <label class="header-name">Edit Supplier</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">	<div class="col-xs-6">
		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $id; ?>" method="post">
			<div class="form-group">
				<label for="name">Supplier Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Enter Supplier's Name" value="<?php if(isset($name)){ echo $name; } ?>">
			</div>

			<div class="form-group">
				<label for="phone">Phone Number</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Supplier's Phone Number" value="<?php if(isset($phone)){ echo $phone; } ?>">
			</div>

			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="text" class="form-control" id="email" name="email" placeholder="Enter Supplier's Email Address" value="<?php if(isset($email)){ echo $email; } ?>">
			</div>

			<div class="form-group">
				<label for="country">Country</label>
				<?php require_once 'Includes/components/countries.php'; ?>
			</div>			
		<button type="submit" name="submit" class="btn btn-primary">Save Info</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
