<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "customers.php";
require_once 'Includes/templates/header.php';

if (isset($_POST['submit'])) {
	$errors = "";
	if($errors==""){
		$fname 		= $_POST['fname'];
		$sname 		= $_POST['sname'];
		$phone 		= $_POST['phone'];
		$org_location = $_POST['org_location'];
		$org_name 	= $_POST['org_name'];
		$national_id 	= $_POST['national_id'];
		$email 		= $_POST['email'];

		
		$qry = "SELECT * FROM tbl_customer WHERE c_fname = '$fname' AND c_lname = '$sname' AND org_name = '$org_name' AND org_location = '$org_location'"; 
		$res = $db->query($qry);
		$rows = mysqli_num_rows($res);
		if($rows > 0){
		
			echo 
			"<script type='text/javascript'>
				swal({
						title: 'Error!',
						text: 'It seems this customer is already in the database',
						type: 'error'
					}, function(){
						window.location.href='customers.php';
					});
			</script>";	
		
		}
		
		else{

			$qry = "INSERT INTO tbl_customer (org_name, org_location, c_fname, c_lname, phone_number, email_address, national_id, state, reg_date) VALUES
			('$org_name', '$org_location', '$fname', '$sname', '$phone', '$email', ' $national_id', 'ACTIVE', now());";
			$db->query($qry) or die($qry);

			echo 
			"<script type='text/javascript'>
				swal({
						title: 'Customer Added Successfully',
						text: 'Press OK to Continue',
						type: 'success'
					}, function(){
						window.location.href='new-sale.php';
					});
			</script>";	
		}
	}
}
?>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Add Customer</label>
    <label class="header-name pull-right"><span id='Time'> </span></label>&nbsp;
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
		
		<form role="form" action="customers-add.php" method="post">
			<div class="form-group">
				<label for="fname">First Name</label>
				<input required type="text" class="form-control" id="fname" name="fname" placeholder="Enter Customers's First Name" value="<?php if(isset($_POST['fname'])){ echo $_POST['fname']; } ?>">
			</div>

			<div class="form-group">
				<label for="sname">Last Name</label>
				<input required type="text" class="form-control" id="sname" name="sname" placeholder="Enter Customers's Surname" value="<?php if(isset($_POST['sname'])){ echo $_POST['sname']; } ?>">
			</div>

			<div class="form-group">
				<label for="national_id">National ID</label>
				<input type="text" class="form-control" id="national_id" name="national_id" placeholder="Enter Customers's National ID" value="<?php if(isset($_POST['national_id'])){ echo $_POST['national_id']; } ?>">
			</div>

			<div class="form-group">
				<label for="phone">Phone Number</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Customers's Phone Number" value="<?php if(isset($_POST['phone'])){ echo $_POST['phone']; } ?>" required>
			</div>

			<div class="form-group">
				<label for="email">Email [Work email address or personal]</label>
				<input type="text" class="form-control" id="email" name="email" placeholder="Enter Customers's Email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>" >
			</div>
			<div class="form-group">
				<label for="org_name">Organisation</label>
				<input type="text" class="form-control" id="org_name" name="org_name" placeholder="Enter Customers's Organisation" value="<?php if(isset($_POST['org_name'])){ echo $_POST['org_name']; } ?>" required>
			</div>
			<div class="form-group">
				<label for="org_location">Location</label>
				<input type="text" class="form-control" id="org_location" name="org_location" placeholder="Enter Organisation Location" value="<?php if(isset($_POST['org_location'])){ echo $_POST['org_location']; } ?>" required>
			</div>						
		<button type="submit" name="submit" class="btn btn-primary">Add Customer</button>
	</form>
</div>
<div class="col-xs-6">
	<p>Please Carefully valify customer identification details before processing with any option. Organisation addresses have to be included with respect to spaces.</p>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
