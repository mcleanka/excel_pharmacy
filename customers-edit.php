<?php 
	require_once 'init.php';
	$current_shop = get_shop_name().' Shop';
	$current_page = "customers.php";
	require_once 'Includes/templates/header.php';

	if (!isset($_GET['id'])) {
		die("No Customer Selected...");
	}
	$id = $_GET['id'];
	$res = $db->query("SELECT * FROM tbl_customer WHERE customer_id = $id");
	while ($row = mysqli_fetch_assoc($res)) {
        $customer_id = $row['customer_id'];
        $fname = $row['c_fname'];
        $sname = $row['c_lname'];
        $phone = $row['phone_number'];
        $email = $row['email_address']; 
        $org_name = $row['org_name'];
        $national_id = $row['national_id'];
	
}

if (isset($_POST['submit'])) {
	$errors = "";
	if($errors==""){
		$fname 		= $_POST['fname'];
		$sname 		= $_POST['sname'];
		$phone 		= $_POST['phone'];
		
		$org_name 	= $_POST['org_name'];
		$national_id 	= $_POST['national_id'];
		$email 		= $_POST['email'];
		$qry = "UPDATE tbl_customer SET c_fname = '$fname', c_lname = '$sname', phone_number = '$phone', email_address = '$email', org_name = '$org_name', national_id = '$national_id', state ='ACTIVE', reg_date = now() WHERE customer_id = $id";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Customer Edit Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='customers.php';
				});
		</script>";	
	}
}
?>

<script type="text/javascript">
	$(document).ready(function(){
		$("option[value='<?php echo $scheme ?>']").attr("selected","true");
	});
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Edit Customer</label>
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
		
		<form role="form" action="customers-edit.php?id=<?php echo $id ?>" method="post">
			<div class="form-group">
				<label for="fname">First Name</label>
				<input required type="text" class="form-control" id="fname" name="fname" placeholder="Enter Customers's First Name" value="<?php if(isset($fname)){ echo $fname; } ?>">
			</div>

			<div class="form-group">
				<label for="sname">Last Name</label>
				<input required type="text" class="form-control" id="sname" name="sname" placeholder="Enter Customers's Surname" value="<?php if(isset($sname)){ echo $sname; } ?>">
			</div>

			<div class="form-group">
				<label for="phone">Phone Number</label>
				<input required type="text" class="form-control" id="phone" name="phone" placeholder="Enter Customers's Phone Number" value="<?php if(isset($phone)){ echo $phone; } ?>">
			</div>

			<div class="form-group">
				<label for="email">Email Address</label>
				<input required type="email" class="form-control" id="email" name="email" placeholder="Enter Customers's Email Address" value="<?php if(isset($email)){ echo $email; } ?>">
			</div>

			<div class="form-group">
				<label for="national_id">NAtional ID</label>
				<input required type="text" class="form-control" id="national_id" name="national_id" placeholder="Enter Customers's Email Address" value="<?php if(isset($national_id)){ echo $national_id; } ?>">
			</div>

			<div class="form-group">
				<label for="org_name">Organisation [ Leave Empty if not on any ]</label>
				<input type="text" class="form-control" id="org_name" name="org_name" placeholder="Enter Customers's Organisation" value="<?php if(isset($org_name)){ echo $org_name; } ?>">
			</div>						
		<button type="submit" name="submit" class="btn btn-primary">Save Info</button>
	</form>
</div>
<div class="col-xs-6">
	<p>Please Carefully valify customer identification details before processing with any option. Organisation addresses have to be included with respect to spaces.</p>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
