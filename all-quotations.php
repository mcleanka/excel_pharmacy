<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "all-quotations.php";
require_once 'Includes/templates/header.php';

$year   = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month  = isset($_GET['month']) ? $_GET['month'] : str_replace("0", "", date('m'));

?>

<script type="text/javascript">
	$(document).ready(function(){
        var table = $('#example').DataTable({
            "order": [[ 0, "desc" ]]
        });

        $('#example tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#button').click( function () {
            table.row('.selected').remove().draw( false );
        });
        $("#info_row").click(function(){
            drug = $(".selected").find("#row_id").text();
            if(drug==''){
                swal("No Selection Made", "Please Select a Qoutation Record", "error");
            }
            else{
                window.location.href='quotation-details.php?id=' + drug;
            }
        });
   });
</script>
<div class="container" id="main-content">
<div class="header">
        <label class="header-name">Quotations For: <?php echo get_month_name($month).', '.$year ?></label>
        <span class="mr-0 pull-right">
            <a class="btn btn-primary" type="button" href="quotations.php"> New Quotation</a>
        </span>
        <hr>
    </div>
<div class="row">
	<div class="col-xs-12">
        <div class="action-btn mb-3">
           <button id="info_row" class="btn btn-warning">More Details</button>
            <a href="PDF/generator/quotations-report.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>" class="btn btn-info">Print Monthly Qoutations</a>
            <a href="PDF/generator/quotations-report2.php?year=<?php echo $year ?>" class="btn btn-primary">Print Yearly Qoutations</a>
			
	   </div>
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">        
            <thead>
                <tr>
                    <th>Quotation #</th>
                    <th>Date Made</th>
                    <th>Written By</th>
                    <th>Organisation</th>
                    <th>Contact Name</th>
                    <th>No. of Products</th>
                    <th>Amount Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $qry = "SELECT tbl_quotation.quotation_id, tbl_q_customer.customer_id, tbl_q_customer.organisation, tbl_quotation.quotation_date, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_q_customer.full_name, (SELECT count(*) FROM tbl_quotation_items WHERE tbl_quotation_items.quotation_id = tbl_quotation.quotation_id) AS items, tbl_quotation.amount_total FROM tbl_quotation, tbl_user, tbl_q_customer WHERE tbl_quotation.user_id = tbl_user.user_id AND tbl_quotation.customer_id = tbl_q_customer.customer_id AND YEAR(tbl_quotation.quotation_date)='$year' AND MONTH(tbl_quotation.quotation_date)='$month';";

                    $res = $db->query($qry);
                    while ($row = mysqli_fetch_assoc($res)):
                        $customer_id    = $row['customer_id'];
                    	$quotation_id 	    = $row['quotation_id'];
                    	$date 		   = date('jS F, Y', strtotime($row['quotation_date']));
                    	$saleby		     = $row['userfname'].' '.$row['usersname'];
                    	$customer 	    = $row['full_name'];
                    	$organisation 	    = $row['organisation'];
                    	$items 		     = $row['items'];
                    	$amount 	     = $row['amount_total'];
                ?>
                <tr>
                    <td id="row_id"><?php echo $quotation_id; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $saleby; ?></td>
                    <td><?php echo $organisation; ?></td>
                    <td><?php echo $customer; ?></td>
                    <td><?php echo $items; ?></td>
                    <td>K<?php echo number_format($amount); ?></td>
                    <td><a href="PDF/generator/customer-quotations-report.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>&amp;organisation=<?php echo $organisation ?>&amp;quotation_id=<?php echo $quotation_id ?>&amp;customer_id=<?php echo $customer_id ?>" class="btn btn-info"><span class="fa fa-print"></span> Print</a></td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>