<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "sales.php";
require_once 'Includes/templates/header.php';

if (!isset($_GET['id'])) {
	die("No Sale Selected");
}
$id = $_GET['id'];
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="container p-fixed" id="main-content">
<div class="header">
        <label class="header-name">Sale Details</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/dashboard-buttons.php'; ?>
        </span>
        <hr>
    </div>

<div class="row">
	<div class="col-xs-12">
		<a href="PDF/generator/receipt.php?id=<?php echo $id ?>" class="btn btn-info">Print Invoice</a>
		<?php 
		$qry = "SELECT tbl_sale.sale_id, tbl_sale.sale_date, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_customer.c_fname AS customerfname, tbl_customer.c_lname AS customersname, tbl_sale.sale_type AS saleType, (SELECT count(*) FROM tbl_sale_drug WHERE tbl_sale_drug.sale_id=tbl_sale.sale_id) AS products, tbl_sale.amount_total FROM tbl_sale,tbl_user,tbl_customer WHERE tbl_sale.user_id = tbl_user.user_id AND tbl_sale.customer_id = tbl_customer.customer_id AND tbl_sale.sale_id = '$id';";
        $res = $db->query($qry);
        while ($row = mysqli_fetch_assoc($res)){
        	$sale_id 	= $row['sale_id'];
        	$date 		= date('jS F, Y', strtotime($row['sale_date']));
        	$saleby		= $row['userfname'].' '.$row['usersname'];
        	$customer 	= $row['customerfname'].' '.$row['customersname'];
        	$saleType 	= $row['saleType'];
        	$products 	= $row['products'];
        	$amount 	= $row['amount_total'];
		}
		?>
		<table class="table table-striped" style="width: 100%">
			<thead>
				<tr>
					<th>Sale Date</th>
					<th>Customer</th>
					<th>Sale Type</th>
					<th>Amount Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $date; ?></td>
					<td><?php echo $customer; ?></td>
					<td><?php echo $saleType; ?></td>
					<td>K<?php echo number_format($amount); ?></td>
				</tr>
			</tbody>
		</table>

		<h3 class="text-center headers" style="background-color: #F9E086; color: #000; font-weight: bold; padding: 5px 0px 5px 0px;">Sale Product</h3>

		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>                    
                    <th>Drug</th>
                    <th>Drug Code</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Line Total</th>
                  <!--   <th>MASM Award</th>
                    <th>Shortfall</th> -->
                </tr>
            </thead>

            <tbody>

            <?php 
            	$res = $db->query("SELECT tbl_drug.name, tbl_drug.price, tbl_drug.code, tbl_sale_drug.* FROM tbl_sale_drug, tbl_drug WHERE tbl_sale_drug.drug_id = tbl_drug.drug_id AND tbl_sale_drug.sale_id = '$id'");

				while ($row = mysqli_fetch_assoc($res)):
				$dname 		= $row['name'];
				$dcode 		= $row['code'];
				$price 		= $row['price'];
				$quantity 	= $row['quantity'];
            ?>
            <tr>
            	<td><?php echo $dname; ?></td> 
            	<td><?php echo $dcode; ?></td> 
            	<td>K<?php echo number_format($price); ?></td>
            	<td><?php echo number_format($quantity); ?></td> 
            	<td>K<?php echo number_format($quantity*$price); ?></td>           
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>
	</div>		
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
