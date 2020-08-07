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
    
       $("#delete_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Payment", "error");
            }
            else{
                swal({
                  title: "Confirm Action",
                  text: "Are you sure you want to Undo this Payment?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Delete",
                  closeOnConfirm: false
                },
                function(){
                    $.post('Ajax/undo_payment.php', {item: item}, function(data){
                        if(data=='success'){;
                            swal({
                                title: 'Action Completed Successfully',
                                text: 'The Payment has Been Undone',
                                type: 'success'
                            }, function(){
                                window.location.href='inventory-payments.php';
                            });
                        }
                        else{
                            swal("Error", "Payment not Deleted", "error");
                        }
                    });
                });
            }
       });

        $("#info_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Payment", "error");
            }
            else{
                window.location.href='PDF/generator/invoice.php?id=' + item;
            }
        });

   });
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Payments History</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn pb-3">
           <button id="delete_row" class="btn btn-danger" style="width: 130px;">Undo Payment</button>
           <button id="info_row" class="btn btn-success" style="width: 140px;">Download PDF</button>
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
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Payment To</th>                    
                    <th>Recorded By</th>
                    <th>Amount Paid</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

            <?php 
            	$res = $db->query("SELECT tbl_payment.*, tbl_supplier.name,tbl_user.first_name,tbl_user.last_name FROM tbl_user,tbl_payment,tbl_supplier,tbl_purchase WHERE tbl_purchase.supplier_id=tbl_supplier.supplier_id AND tbl_payment.user_id=tbl_user.user_id AND tbl_payment.purchase_id=tbl_purchase.purchase_id");

				while ($row = mysqli_fetch_assoc($res)):
				$pid = $row['payment_id']; 
				$collectedby = $row['first_name'].' '.$row['last_name'];
				$supplier = $row['name'];
				$amount = $row['amount_paid'];
				$date = date('jS F, Y', strtotime($row['payment_date']));
            ?>
            <tr>
                <td id="row_id"><?php echo $pid; ?></td>                
                <td><?php echo $supplier; ?></td>
                <td><?php echo $collectedby; ?></td>
                <td>K<?php echo number_format($amount); ?></td>
                <td><?php echo $date; ?></td>
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>

	    <div style="margin-top: 20px;">

	    </div>
	</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
