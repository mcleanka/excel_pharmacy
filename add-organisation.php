<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "customers.php";
require_once 'Includes/templates/header.php';

if (isset($_POST['submit'])) {
	$errors = "";
	if($errors==""){
		$phone 		= $_POST['phone'];
		$org_location = $_POST['org_location'];
		$org_name 	= $_POST['org_name'];
		$email 		= $_POST['email'];

		
		$qry = "SELECT * FROM tbl_organ WHERE organ_name = '$org_name' AND organ_location = '$org_location'"; 
		$res = $db->query($qry);
		$rows = mysqli_num_rows($res);
		if($rows > 0){
		
			echo 
			"<script type='text/javascript'>
				swal({
						title: 'Error!',
						text: 'It seems this Organization is already in the database',
						type: 'error'
					}, function(){
						window.location.href='customers.php';
					});
			</script>";	
		
		}
		
		else{

			$qry = "INSERT INTO tbl_organ (organ_name, organ_location, organ_tel, organ_email, state, reg_date) VALUES
			('$org_name', '$org_location', '$phone', '$email', 'ACTIVE', 'now()');";
			$db->query($qry) or die($qry);

			echo 
			"<script type='text/javascript'>
				swal({
						title: 'Organization Added Successfully',
						text: 'Press OK to Continue',
						type: 'success'
					}, function(){
						window.location.href='customer-add.php';
					});
			</script>";
			
		}
	}
}
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="row">
    <div class="col-xs-6">
        <h2 class="module-header">Add New Organization</h2>
    </div>
</div>

<legend style="margin-top: 10px;"></legend>

<div class="row" style="margin-top: 20px;">
	<div class="col-xs-6">
		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" action="add-organisation.php" method="post" style="margin-left: 15px;">
			<div class="form-group">
				<label for="org_name">Organization Name</label>
				<input required type="text" class="form-control" id="org_name" name="org_name" placeholder="Enter Customer's Organization Name" value="<?php if(isset($_POST['org_name'])){ echo $_POST['org_name']; } ?>">
			</div>

			<div class="form-group">
				<label for="org_location">Organization Location [Area]</label>
				<input required type="text" class="form-control" id="org_location" name="org_location" placeholder="Enter Organization Location" value="<?php if(isset($_POST['org_location'])){ echo $_POST['org_location']; } ?>">
			</div>

			<div class="form-group">
				<label for="phone">Phone Number</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Contact Tel" value="<?php if(isset($_POST['phone'])){ echo $_POST['phone']; } ?>" required>
			</div>

			<div class="form-group">
				<label for="email">Email [Work place email address]</label>
				<input required type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>" >
			</div>					
		<button type="submit" name="submit" class="btn btn-primary">Add Customer</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
