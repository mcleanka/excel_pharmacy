<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory.php";
require_once 'Includes/templates/header.php';

$id  = escape($_GET['id']);
$qry = "SELECT * FROM tbl_stock WHERE stock_id=$id";
$res = $db->query($qry) or die($qry);
if($res->num_rows == 0){
	die("Item not found");
}
$qry = "SELECT tbl_drug.*,tbl_shop.name AS shopname,tbl_shop.shop_id,tbl_stock.stock_id,tbl_stock.quantity, tbl_stock.last_updated FROM tbl_drug,tbl_shop,tbl_stock WHERE tbl_stock.drug_id=tbl_drug.drug_id AND tbl_stock.shop_id=tbl_shop.shop_id AND tbl_stock.quantity>0 AND tbl_stock.stock_id=$id";
$res = $db->query($qry);

while ($row = mysqli_fetch_assoc($res)) {
	$dname 		= $row['name'];
	$sname 		= $row['shopname'];
	$sid 		= $row['shop_id'];
	$quantity 	= $row['quantity'];
}
if (isset($_POST['submit'])) {
	$errors = "";
	if(empty($_POST['quantity'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The transfer quantity field is required.</div>";
	}

	if($errors==""){
		$quantity = $_POST['quantity'];
		$shop = $_POST['transferto'];

		$res = $db->query("SELECT * FROM tbl_stock WHERE stock_id=$id");
		while($row = mysqli_fetch_assoc($res)){
			$drug_id = $row['drug_id'];
		}

		$res = $db->query("SELECT * FROM tbl_stock WHERE drug_id=$drug_id AND shop_id=$shop");
		if($res->num_rows>0){
			while ($row = mysqli_fetch_assoc($res)) {
				$new_stock_id = $row['stock_id'];
			}
			$db->query("UPDATE tbl_stock SET quantity=quantity+$quantity WHERE stock_id=$new_stock_id");

		}
		else{
			$db->query("INSERT INTO tbl_stock (drug_id,shop_id,quantity,last_updated,state) VALUES($drug_id,$shop,$quantity,now(),'ACTIVE')");
		}
		$db->query("UPDATE tbl_stock SET quantity=quantity-$quantity WHERE stock_id=$id AND shop_id=$sid");

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Stock Transfered Successfully',
					text: 'Press OK to Continue',
					type: 'success'
				}, function(){
					window.location.href='inventory.php';
				});
		</script>";	
	}
}
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Transfer Stock</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
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
		
		<form role="form" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $id; ?>" method="post">
			<div class="form-group">
				<label for="name">Drug Name</label>
				<input type="text" class="form-control" id="name" name="name" placeholder="" value="<?php if(isset($dname)){ echo $dname; } ?>" disabled>
			</div>

			<div class="form-group">
				<label for="phone">Current Shop</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="" value="<?php if(isset($sname)){ echo $sname.' Shop'; } ?>" disabled>
			</div>

			<div class="form-group">
				<label for="email">Available Quantity</label>
				<input type="text" class="form-control" id="email" name="email" placeholder="" value="<?php if(isset($quantity)){ echo number_format($quantity); } ?>" disabled>
			</div>

			<div class="form-group">
				<label for="transferto">Transfer To:</label>				
				<select name="transferto" class="shops form-control">
				<?php 
				$res = $db->query("SELECT * FROM tbl_shop WHERE state='active' AND shop_id<>$sid");
				while ($row = mysqli_fetch_assoc($res)):
					$shop_id = $row['shop_id'];
					$shopname = $row['name'];
				?>
				<option value="<?php echo $shop_id ?>"><?php echo $shopname.' Shop' ?></option>
				<?php endwhile; ?>
				</select>
				<script type="text/javascript">
			      	$(document).ready(function() {
			        	$(".shops").select2();
			    	});
			    </script>
			</div>

			<div class="form-group">
				<label for="quantity">Transfer Quantity</label>
				<input type="number" min="1" max="<?php echo $quantity ?>" class="form-control" id="quantity" name="quantity" placeholder="" value="1">
			</div>

		<button type="submit" name="submit" class="btn btn-primary">Transfer</button>
		<a href="#" class="btn btn-danger" onclick="window.location.href='inventory.php'">Cancel</a>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
