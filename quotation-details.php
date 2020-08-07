<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "all-quotations.php";
require_once 'Includes/templates/header.php';

if (!isset($_GET['id'])) {
	die("No Quotation Selected");
}
$id = $_GET['id'];
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="container p-fixed" id="main-content">
<div class="header">
        <label class="header-name">Quotation Details</label>
        <span class="mr-0 pull-right">
            <a class="btn btn-primary" type="button" href="quotations.php"> New Quotation</a>
            <a class="btn btn-info" type="button" href="all-quotations.php"> Back</a>
        </span>
        <hr>
    </div>
<div class="row">
	<div class="col-xs-12">
		<?php 
		$qry = "SELECT tbl_quotation.quotation_id, tbl_quotation.quotation_date, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_q_customer.full_name, tbl_q_customer.organisation, (SELECT count(*) FROM tbl_quotation_items WHERE tbl_quotation_items.quotation_id=tbl_quotation.quotation_id) AS products, tbl_quotation.amount_total FROM tbl_quotation, tbl_user, tbl_q_customer WHERE tbl_quotation.user_id = tbl_user.user_id AND tbl_quotation.customer_id = tbl_q_customer.customer_id AND tbl_quotation.quotation_id = '$id';";
        $res = $db->query($qry);
        while ($row = mysqli_fetch_assoc($res)){
        	$quotation_id 	= $row['quotation_id'];
        	$date 		= date('jS F, Y', strtotime($row['quotation_date']));
        	$saleby		= $row['userfname'].' '.$row['usersname'];
        	$customer 	= $row['full_name'];
        	$organisation 	= $row['organisation'];
        	$products 	= $row['products'];
        	$amount 	= $row['amount_total'];
		}
		?>
		<table class="table table-striped" style="width: 100%">
			<thead>
				<tr>
					<th>Quotation Date</th>
					<th>Customer</th>
					<th>Organisation</th>
					<th>Amount Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $date; ?></td>
					<td><?php echo $customer; ?></td>
					<td><?php echo $organisation; ?></td>
					<td>K<?php echo number_format($amount); ?></td>
				</tr>
			</tbody>
		</table>

		<h3 class="text-center headers" style="background-color: #F9E086; color: #000; font-weight: bold; padding: 5px 0px 5px 0px;">Quotation Product(s)</h3>

		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>                    
                    <th>Drug</th>
                    <th>Drug Code</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Line Total</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            	$res = $db->query("SELECT tbl_drug.name, tbl_drug.price, tbl_drug.code, tbl_quotation_items.* FROM tbl_quotation_items, tbl_drug WHERE tbl_quotation_items.drug_id = tbl_drug.drug_id AND tbl_quotation_items.quotation_id = '$id'");

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
