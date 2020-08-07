<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "users.php";
require_once 'Includes/templates/header.php';

if (!isset($_GET['id'])) {
	die('No User Selected');
}
$id = $_GET['id'];
$res = $db->query("SELECT * FROM tbl_user WHERE user_id=$id");
while($row = mysqli_fetch_assoc($res)){
	$fname 		= $row['first_name'];
	$sname 		= $row['last_name'];
	$phone 		= $row['phone_number'];
	$email 		= $row['email_address'];
	$acctype	= $row['account_type'];
	$shop 		= $row['shop'];
	$username 	= $row['username'];
}

if (isset($_POST['submit'])) {
	$errors = "";
	$username = $_POST['username'];
	
	if($errors==""){
		$fname 		= $_POST['fname'];
		$sname 		= $_POST['sname'];
		$phone 		= $_POST['phone'];
		$email 		= $_POST['email'];
		$acctype	= $_POST['acctype'];
		$shop 		= $_POST['shop'];

		$qry = "UPDATE tbl_user SET first_name='$fname',last_name='$sname',username='$username',phone_number='$phone',email_address='$email',account_type='$acctype',shop='$shop' WHERE user_id=$id;";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'User Edited Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='users.php';
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
        <label class="header-name">Edit User</label>
        <a href="users.php" style="width: 110px;" class="btn btn-danger mr-0 pull-right"><< Back</a>
        <hr>
    </div>
<div class="row">
	<div class="col-xs-12">
		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>
			<div class="row">
				<div class="col-xs-6">
					<?php echo $errors ?>
				</div>
			</div>

		<?php endif; endif;?>
		
		<form role="form" action="users-edit.php?id=<?php echo $id ?>" method="post">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label for="fname">First Name</label>
						<input required type="text" class="form-control" id="fname" name="fname" placeholder="Enter User's First Name" value="<?php if(isset($fname)){ echo $fname; } ?>">
					</div>

					<div class="form-group">
						<label for="sname">Last Name</label>
						<input required type="text" class="form-control" id="sname" name="sname" placeholder="Enter User's Surname" value="<?php if(isset($sname)){ echo $sname; } ?>">
					</div>

					<div class="form-group">
						<label for="phone">Phone Number</label>
						<input required type="text" class="form-control" id="phone" name="phone" placeholder="Enter User's Phone Number" value="<?php if(isset($phone)){ echo $phone; } ?>">
					</div>

					<div class="form-group">
						<label for="email">Email Address</label>
						<input required type="email" class="form-control" id="email" name="email" placeholder="Enter User's Email Address" value="<?php if(isset($email)){ echo $email; } ?>">
					</div>

					<button type="submit" name="submit" class="btn btn-primary">Save Info</button>
				</div>

				<div class="col-xs-6">
					<div class="form-group">
						<label for="username">Choose Username</label>
						<input required type="text" class="form-control" id="username" name="username" placeholder="Choose User's Username" value="<?php if(isset($username)){ echo $username; } ?>">
					</div>

					<div class="form-group">
						<label for="acctype">Account Type</label>
						<select name="acctype" class="form-control">
							<option value="pharmacist">Pharmacist</option>
							<option value="admin">Adminstrator</option>
						</select>

						<script type="text/javascript">
							var acc = '<?php echo $acctype ?>';
							$("option[value='"+acc+"']").attr("selected","true");
						</script>
					</div>

					<div class="form-group">
						<label for="shop">Shop:</label>				
						<select name="shop" class="shops form-control">
						<?php 
						$res = $db->query("SELECT * FROM tbl_shop WHERE state='ACTIVE'");
						while ($row = mysqli_fetch_assoc($res)):
							$shop_id = $row['shop_id'];
							$shopname = $row['name'];
						?>
						<option value="<?php echo $shop_id ?>"><?php echo $shopname.' Shop' ?></option>
						<?php endwhile; ?>
						</select>
						<script type="text/javascript">
							var acc = '<?php echo $shop ?>';
							$(".shops option[value='"+acc+"']").attr("selected","true");
						</script>

						<script type="text/javascript">
					      	$(document).ready(function() {
					        	$(".shops").select2();
					    	});
					    </script>
					</div>					
				</div>
			</div>
		
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
