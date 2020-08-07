<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';
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
    
        $("#payment").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Purchase", "error");
            }
            else{
                window.location.href='inventory-record-payment.php?id=' + item;
            }
        });

        $("#info_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Purchase", "error");
            }
            else{
                window.location.href='inventory-purchase-details.php?id=' + item;
            }
        });

   });
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Suppliers List</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn pb-3">
           <a href="inventory-new-purchase.php" class="btn btn-primary">New Purchase</a>
           <button id="payment" class="btn btn-info" style="width: 130px;">Record Payment</button>
           <button id="info_row" class="btn btn-warning">More Details</button>

           <div class="checkbox" class="pull-right" id="balanced" style="float: right;">
				<label><input type="checkbox" id="cb" <?php if(isset($_GET['balances'])){ echo "checked='checked'"; } ?>><b> With Balance Only</b></label>
			</div>

			<script type="text/javascript">
				$(document).ready(function(){
					$("#cb").change(function(){
						var status = $("#cb").val();
						if ($("#cb").is(":checked")) {
							window.location.href='inventory-purchases.php?balances=on';
						}
						else{
							window.location.href='inventory-purchases.php';
						}
					});
				});
			</script>
        </div>

        <div>
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>                    
                    <th>Purchase Date</th>
                    <th>No. of Items</th>
                    <th>Type</th>
                    <th>Amount Total</th>
                    <th>Balance</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            	if(isset($_GET['balances'])){
    				$res = $db->query("SELECT *, (SELECT name FROM tbl_supplier WHERE tbl_supplier.supplier_id=tbl_purchase.supplier_id) AS sname, (SELECT count(*) FROM tbl_purchase_item WHERE tbl_purchase_item.purchase_id=tbl_purchase.purchase_id) AS items,
                       (SELECT SUM(amount_paid) FROM tbl_payment WHERE tbl_payment.purchase_id=tbl_purchase.purchase_id) AS totalpayments FROM tbl_purchase WHERE state='UNPAID' ORDER BY purchase_id DESC");            		
	           	}
            	else{
					$res = $db->query("SELECT *, (SELECT name FROM
                     tbl_supplier WHERE tbl_supplier.supplier_id=tbl_purchase.supplier_id) AS sname,
                
                      (SELECT count(*) FROM tbl_purchase_item WHERE tbl_purchase_item.purchase_id=tbl_purchase.purchase_id) AS items,
                     (SELECT SUM(amount_paid) FROM tbl_payment WHERE tbl_payment.purchase_id=tbl_purchase.purchase_id) AS totalpayments FROM tbl_purchase ORDER BY purchase_id DESC");            		
            	
                }

				while ($row = mysqli_fetch_assoc($res)):
				$pid = $row['purchase_id']; 
				$sname = $row['sname'];
				$type = $row['purchase_type'];
				$amount = $row['amount_total'];
				$items = $row['items'];
                // $expiry_date = date('jS F, Y', strtotime($row['expiry_date']));
				$payments = $row['totalpayments']=='null' ? 0: $row['totalpayments'];
				$date = date('jS F, Y', strtotime($row['purchase_date']));
            ?>
            <tr>
                <td id="row_id"><?php echo $pid; ?></td>
                <td><?php echo $sname; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $items; ?></td>
                <td style="text-transform: uppercase;"><?php echo $type; ?></td>
                <td>K<?php echo number_format($amount); ?></td>
                <td>K<?php echo number_format(($amount-$payments)); ?></td>
              
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>

        </div>

	    <div style="margin-top: 20px;">

	    </div>
	</div>
</div>


<?php require_once 'Includes/templates/footer.php'; ?>
