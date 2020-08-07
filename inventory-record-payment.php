<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';
$todayDate =  date('Y/m/d');

if(!isset($_GET['id'])){
	die("No Purchase Selected");
}
$id = $_GET['id'];

$res = $db->query("SELECT SUM(amount_paid) AS total FROM tbl_payment WHERE purchase_id=$id");
while ($row = mysqli_fetch_assoc($res)) {
	$totalpaid = $row['total']!='null' ? $row['total']: 0;
}

$res = $db->query("SELECT amount_total FROM tbl_purchase WHERE purchase_id=$id");
while ($row = mysqli_fetch_assoc($res)) {
	$amount_total = $row['amount_total'];
}

$balance = $amount_total-$totalpaid;
if ($balance<=0) {
	$db->query("UPDATE tbl_purchase SET state='PAID' WHERE purchase_id=$id");
}

if (isset($_POST['submit'])) {
	$amount = str_replace(",", "", $_POST['amount']);
	$errors = "";
	if(empty($_POST['amount'])){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The payment amount field is required.</div>";
	}
	if(!is_numeric($amount)){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The payment amount format is invalid.</div>";
	}
	if($amount > $balance){
		$errors.="<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>The amount paid is greater than the balance K".number_format($balance).".</div>";
	}


	if($errors==""){
		
		$date 		= str_replace("/", "-", $_POST['date']);
		$user_id 	= $_SESSION['citipharm']['user_id'];

		$qry = "INSERT INTO tbl_payment 
		(purchase_id,user_id,amount_paid,payment_date,state) VALUES
		('$id','$user_id','$amount','$date','active')";
		$db->query($qry) or die($qry);

		$res = $db->query("SELECT SUM(amount_paid) AS total FROM tbl_payment WHERE purchase_id=$id");
		while ($row = mysqli_fetch_assoc($res)) {
			$totalpaid = $row['total']!='null' ? $row['total']: 0;
		}

		$res = $db->query("SELECT amount_total FROM tbl_purchase WHERE purchase_id=$id");
		while ($row = mysqli_fetch_assoc($res)) {
			$amount_total = $row['amount_total'];
		}

		$balance = $amount_total-$totalpaid;
		if ($balance<=0) {
			$db->query("UPDATE tbl_purchase SET state='PAID' WHERE purchase_id=$id");
		}

		echo 
		"<script type='text/javascript'>
			swal({
					title: 'Payment Recorded Successfully',
					text: 'Remaining Balance K".number_format($balance)."',
					type: 'success'
				}, function(){
					window.location.href='inventory-payments.php';
				});
		</script>";	
	}
}
?>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Record New Payment</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">
	<div class="col-xs-6">
		<?php
			$res = $db->query("SELECT tbl_purchase.*, tbl_supplier.name FROM tbl_purchase, tbl_supplier WHERE purchase_id=$id AND tbl_purchase.supplier_id=tbl_supplier.supplier_id");
			while ($row = mysqli_fetch_assoc($res)) {
				$supplier = $row['name'];
			}
			if(!isset($_POST['submit'])){
				if($balance<=0){
					echo 
					"<script type='text/javascript'>
						swal({
								title: 'No Payment Balance',
								text: 'No payment required',
								type: 'info'
							}, function(){
								window.location.href='inventory-purchases.php';
							});
					</script>";
				}
			}
			
		?>
		<h3 style="margin-left: 15px;">Payment to: <?php echo $supplier; ?>,<br> Current Balance: K<?php echo number_format($balance) ?></h3>

		<?php 
			if(!isset($_POST['submit'])):
		?>
				
		<?php else: if($errors!=""): ?>

		<?php echo $errors ?>

		<?php endif; endif;?>

		
		<form role="form" action="inventory-record-payment.php?id=<?php echo $id ?>" method="post">
			
			<div class="form-group">
				<label for="amount">Amount Paid [ MWK ]</label>
				<input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Payment Amount" value="<?php if(isset($_POST['amount'])){ echo $_POST['amount']; } ?>">
			</div>

			<div class="form-group">
				<label for="date">Payment Date</label>
				<input type="text" class="form-control" id="date" name="date" value="<?php echo $todayDate; ?>">
			</div>

			<script type="text/javascript">
			$("#date").datepicker({dateFormat:'yy/mm/dd', autoOpen: false, maxDate: '0'})
			</script>
						
		<button type="submit" name="submit" class="btn btn-primary">Record Payment</button>
	</form>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
