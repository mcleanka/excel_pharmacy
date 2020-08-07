<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "password.php";
require_once 'Includes/templates/header.php';

if (isset($_POST['submit'])) {
	$errors = "";
	$oldpassword 	= $_POST['old'];
	$newpassword1	= $_POST['pass1'];
	$newpassword2	= $_POST['pass2'];
	if($newpassword1 != $newpassword2){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The New Passwords do not Match</div>";
	}
	$user_id = get_user_id();
	$res = $db->query("SELECT password FROM tbl_user WHERE user_id=$user_id");
	while ($row = mysqli_fetch_assoc($res)) {
		$cpass = $row['password'];
	}

	if($cpass != md5($oldpassword)){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The Current Password is Incorrect</div>";
	}	

	if($errors==""){
		$insertpassword = md5($newpassword1);
		$qry = "UPDATE tbl_user SET password='$insertpassword' WHERE user_id='$user_id'";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Password Changed Successfully',
					text: 'You will be logged out to relog in',
					type: 'success'
				}, function(){
					window.location.href='logout.php';
				});
		</script>";	
	}
}
?>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Change Your Password</label>
    <label class="header-name pull-right"><span id='Time'> </span></label>&nbsp;
    <hr>
</div>

<div class="row">
	<div class="col-xs-6">
		<?php 
			if(!isset($_POST['submit'])):
		?>
		
		<div class="alert alert-info">Please Choose a Secure Password</div>
		
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>
		
		<form role="form" action="password.php" method="post">
			<div class="form-group">
				<label for="old">Current Password</label>
				<input type="password" class="form-control" id="old" name="old" placeholder="Enter you current password" required="true">
			</div>

			<div class="form-group">
				<label for="pass1">Choose New Password</label>
				<input type="password" class="form-control" id="pass1" name="pass1" placeholder="Enter new password" required="true">
			</div>

			<div class="form-group">
				<label for="pass2">Confirm New Password</label>
				<input type="password" class="form-control" id="pass2" name="pass2" placeholder="Re-Enter new password" required="true">
			</div>			
		<button type="submit" name="submit" class="btn btn-primary">Change Password</button>
	</form>
</div>
<div class="col-xs-6">
	<p class="note">Make sure the password is simple and easy to memories. If you fail to recover your password please ask help from pharmacy Manager</p>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
