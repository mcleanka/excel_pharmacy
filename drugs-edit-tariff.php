<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "drugs.php";
require_once 'Includes/templates/header.php';

$id  = escape($_GET['id']);
$qry = "SELECT * FROM tbl_tariff WHERE tariff_id=$id";
$res = $db->query($qry) or die($qry);
if($res->num_rows == 0){
	die("Item not found");
}

while ($row = mysqli_fetch_assoc($res)) {
	$name 	= $row['description'];
	$code 	= $row['name'];
	$vip 	= $row['vip'];
	$exec 	= $row['exec'];
}
if (isset($_POST['submit'])) {
	$errors = "";
	if(empty($_POST['name'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The tariff name field is required.</div>";
	}
	if(empty($_POST['code'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The tariff code field is required.</div>";
	}
	if(empty($_POST['vip'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The VIP discount field is required.</div>";
	}
	if(empty($_POST['exec'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The EXEC discount field is required.</div>";
	}
	if(!is_numeric($_POST['vip'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Invalid VIP discount value.</div>";
	}
	if(!is_numeric($_POST['exec'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Invalid EXEC discount value.</div>";
	}

	if($errors==""){
		$name 	= $_POST['name'];
		$code 	= $_POST['code'];
		$vip 	= $_POST['vip'];
		$exec 	= $_POST['exec'];

		$qry = "UPDATE tbl_tariff SET name='$code', description='$name', vip='$vip', exec=$exec WHERE tariff_id=$id";
		$db->query($qry) or die($qry);

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Tariff Edited Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='drugs-tariffs.php';
				});
		</script>";	
	}
}
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="row">
    <div class="col-xs-6">
        <h2 class="module-header">Edit Tariff</h2>
    </div>

    <div class="col-xs-6" style="margin-top: 20px;">
        <?php include 'Includes/components/drugs-top-buttons.php'; ?>
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
		
		<form role="form" action="drugs-edit-tariff.php?id=<?php echo $id; ?>" method="post" style="margin-left: 15px;">
			<div class="form-group">
				<label for="name">Tariff Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="Enter Tariff Name" value="<?php if(isset($name)){ echo $name; } ?>">
			</div>

			<div class="form-group">
				<label for="code">Tariff Code</label>
				<input type="text" class="form-control" id="code" name="code" placeholder="Enter Tariff Code" value="<?php if(isset($code)){ echo $code; } ?>">
			</div>

			<div class="form-group">
				<label for="vip">VIP Discount [ % ]</label>
				<input type="number" min="0" max="100" class="form-control" id="vip" name="vip" value="<?php if(isset($vip)){ echo $vip; } ?>">
			</div>

			<div class="form-group">
				<label for="exec">EXEC Discount [ % ]</label>
				<input type="number" min="0" max="100" class="form-control" id="exec" name="exec" value="<?php if(isset($exec)){ echo $exec; } ?>">
			</div>			
		<button type="submit" name="submit" class="btn btn-primary">Save Info</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
