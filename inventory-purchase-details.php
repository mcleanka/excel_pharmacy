<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';

if (!isset($_GET['id'])) {
	die("No Purchase Selected");
}
$id = $_GET['id'];
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="row">
    <div class="col-xs-6">
        <h2 class="module-header">Purchase Details</h2>
    </div>

    <div class="col-xs-6" style="margin-top: 20px;">
        <?php include 'Includes/components/inventory-top-buttons.php'; ?>
    </div>
</div>

<legend style="margin-top: 10px;"></legend>

<div class="row" style="margin-top: 20px;">
	<div class="col-xs-12">
		<h3 class="text-center headers" style="background-color: #F9E086; color: #000; font-weight: bold; padding: 5px 0px 5px 0px;">Purchase Details as at <?php echo date('jS F, Y'); ?></h3>
		<?php 
		$res = $db->query("SELECT *, (SELECT name FROM tbl_supplier WHERE tbl_supplier.supplier_id=tbl_purchase.supplier_id) AS sname, (SELECT count(*) FROM tbl_purchase_item WHERE tbl_purchase_item.purchase_id=tbl_purchase.purchase_id) AS items, (SELECT SUM(amount_paid) FROM tbl_payment WHERE tbl_payment.purchase_id=tbl_purchase.purchase_id) AS totalpayments FROM tbl_purchase WHERE purchase_id=$id ORDER BY purchase_id DESC");            		

		while ($row = mysqli_fetch_assoc($res)){
			$pid = $row['purchase_id']; 
			$sname = $row['sname'];
			$type = $row['purchase_type'];
			$amount = $row['amount_total'];
			$items = $row['items'];
			$payments = $row['totalpayments']=='null' ? 0: $row['totalpayments'];
			$date = date('jS F, Y', strtotime($row['purchase_date']));
		}
		?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Purchase Date</th>
					<th>Purchase Type</th>
					<th>Supplier</th>
					<th>Amount Total</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $date; ?></td>
					<td style="text-transform: uppercase;"><?php echo $type; ?></td>
					<td style="text-transform: uppercase;"><?php echo $sname; ?></td>
					<td>K<?php echo number_format($amount); ?></td>
					<td>K<?php echo number_format(($amount-$payments)); ?></td>
				</tr>
			</tbody>
		</table>

		<h3 class="text-center headers" style="background-color: #F9E086; color: #000; font-weight: bold; padding: 5px 0px 5px 0px;">Purchase Items</h3>

		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>                    
                    <th>Drug</th>
                    <th>Drug Code</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Line Total</th>
                    <th>Expiry date</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            	$res = $db->query("SELECT tbl_drug.name, tbl_drug.code, tbl_purchase_item.* FROM tbl_purchase_item,tbl_drug WHERE tbl_purchase_item.drug_id=tbl_drug.drug_id AND tbl_purchase_item.purchase_id=$id");

				while ($row = mysqli_fetch_assoc($res)):
				$dname 		= $row['name'];
				$dcode 		= $row['code'];
				$price 		= $row['unit_price'];
				$quantity 	= $row['quantity'];
				$expDate 	= date('jS F, Y', strtotime($row['expiry_date']));
            ?>
            <tr>
            	<td><?php echo $dname; ?></td> 
            	<td><?php echo $dcode; ?></td> 
            	<td>K<?php echo number_format($price); ?></td>
            	<td><?php echo number_format($quantity); ?></td> 
            	<td>K<?php echo number_format($quantity*$price); ?></td>
            	<td>K<?php echo $expDate; ?></td>             
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>

		<h3 class="text-center" style="background-color: #F9E086; color: #000; font-weight: bold; padding: 5px 0px 5px 0px;">Payments for Purchase</h3>
		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>                    
                    <th>Date</th>
                    <th>Amount Paid</th>
                    <th>Recorded By</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            	$res = $db->query("SELECT tbl_payment.*, tbl_supplier.name,tbl_user.first_name,tbl_user.last_name FROM tbl_user,tbl_payment,tbl_supplier,tbl_purchase WHERE tbl_purchase.supplier_id=tbl_supplier.supplier_id AND tbl_payment.user_id=tbl_user.user_id AND tbl_payment.purchase_id=tbl_purchase.purchase_id AND tbl_purchase.purchase_id=$id");

				while ($row = mysqli_fetch_assoc($res)):
				$pid = $row['payment_id']; 
				$collectedby = $row['first_name'].' '.$row['last_name'];
				$supplier = $row['name'];
				$amount = $row['amount_paid'];
				$date = date('jS F, Y', strtotime($row['payment_date']));
            ?>
            <tr>
            	<td><?php echo $date; ?></td>                
                <td>K<?php echo number_format($amount); ?></td>
                <td><?php echo $collectedby; ?></td>                
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>
	</div>		
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
