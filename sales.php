<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "sales.php";
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
                swal("No Selection Made", "Please Select a Sale Record", "error");
            }
            else{
                window.location.href='sale-details.php?id=' + drug;
            }
        });

        $("#download_row").click(function(){
            drug = $(".selected").find("#row_id").text();
            if(drug==''){
                swal("No Selection Made", "Please Select a Sale Record", "error");
            }
            else{
                window.location.href='PDF/generator/receipt.php?id=' + drug;
            }
        });
   });
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">Sales: <?php echo get_month_name($month).', '.$year ?></label>
    <span class="pull-right mt-0 ml-2">
        <a href="new-sale.php" style="width: 110px;" class="btn btn-primary">New Sale</a>
    </span>
    <span class="mr-2 pull-right">
        <form class="form-inline" role="form"> 
            <span class="filter">Filter</span> 
            <div class="form-group">  
                <label class="" for="name">Year</label>  
                <select class="form-control syear" name="year">
                    <?php 
                    $currentyr = date('Y');
                    for($i=$currentyr;$i>2001;$i--):
                    ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>

                <script type="text/javascript">
                    $(".syear > option[value=<?php echo $year ?>]").attr("selected","true");
                </script>
            </div>

            <div class="form-group"> 
                <label class="" for="name" style="margin-left: 10px;">Month</label>  
                <select class="form-control smonth" name="month">
                    <option value="1">JAN</option>
                    <option value="2">FEB</option>
                    <option value="3">MAR</option>
                    <option value="4">APR</option>
                    <option value="5">MAY</option>
                    <option value="6">JUN</option>
                    <option value="7">JUL</option>
                    <option value="8">AUG</option>
                    <option value="9">SEP</option>
                    <option value="10">OCT</option>
                    <option value="11">NOV</option>
                    <option value="12">DEC</option>
                </select>

                <script type="text/javascript">
                    $(".smonth > option[value=<?php echo $month ?>]").attr("selected","true");
                </script>
            </div>
            <button type="submit" class="btn btn-info">Go!</button>
            <input type="hidden" value="<?php echo $year ?>" id="post_year"/>
            <input type="hidden" value="<?php echo $month ?>" id="post_month"/>  
        </form>
    </span>
    <hr>
</div>

<div class="row">
	<div class="col-xs-12">
        <div class="action-btn py-3">
           <button id="info_row" class="btn btn-warning">More Details</button>
           <button id="download_row" style="width: 140px;" class="btn btn-success">Print Invoice</button>
            <a href="PDF/generator/sales-report.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>" class="btn btn-info">Print Monthly Sales Report</a>
            <a href="PDF/generator/sales-report2.php?year=<?php echo $year ?>" class="btn btn-primary">Print Yearly Sales Report</a>
			<div class="checkbox" class="pull-right" id="balanced" style="float: right;">
                <label><input type="checkbox" id="cb" <?php if(isset($_GET['credit_only'])){ echo "checked='checked'"; } ?>><b> Sales on Credit Only</b></label>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#cb").change(function(){
                        var status = $("#cb").val();
                        if ($("#cb").is(":checked")) {
                            window.location.href='sales.php?credit_only=on';
                        }
                        else{
                            window.location.href='sales.php';
                        }
                    });
                });
            </script>
			
	   </div>
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">        
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sale Date</th>
                    <th>Sold By</th>
                    <th>Customer</th>
                    <th>Sale Type</th>
                    <th>No. of Products</th>
                    <th>Amount Total</th>
                    <th>Action</th>
                    <!-- <th>Shortfall</th> -->
                </tr>
            </thead>

            <tbody>
                <?php
                  if(isset($_GET['credit_only'])){
                    $qry = "SELECT tbl_sale.sale_id, tbl_sale.sale_date, tbl_customer.customer_id, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_customer.c_fname AS customerfname, tbl_customer.C_lname AS customersname,tbl_sale.sale_type AS saleType, (SELECT count(*) FROM tbl_sale_drug WHERE tbl_sale_drug.sale_id = tbl_sale.sale_id) AS items, tbl_sale.amount_total FROM tbl_sale, tbl_user, tbl_customer WHERE tbl_sale.user_id = tbl_user.user_id AND tbl_sale.customer_id = tbl_customer.customer_id AND YEAR(tbl_sale.sale_date)='$year' AND MONTH(tbl_sale.sale_date)='$month' AND sale_type = 'credit';";
                }
                else{
                    $qry = "SELECT tbl_sale.sale_id, tbl_customer.customer_id, tbl_sale.sale_date, tbl_user.first_name AS userfname, tbl_user.last_name AS usersname, tbl_customer.c_fname AS customerfname, tbl_customer.C_lname AS customersname,tbl_sale.sale_type AS saleType, (SELECT count(*) FROM tbl_sale_drug WHERE tbl_sale_drug.sale_id = tbl_sale.sale_id) AS items, tbl_sale.amount_total FROM tbl_sale, tbl_user, tbl_customer WHERE tbl_sale.user_id = tbl_user.user_id AND tbl_sale.customer_id = tbl_customer.customer_id AND YEAR(tbl_sale.sale_date)='$year' AND MONTH(tbl_sale.sale_date)='$month';";
                }

                $res = $db->query($qry);
                while ($row = mysqli_fetch_assoc($res)):
                    $customer_id    = $row['customer_id'];
                	$sale_id 	    = $row['sale_id'];
                	$date 		   = date('jS F, Y', strtotime($row['sale_date']));
                	$saleby		     = $row['userfname'].' '.$row['usersname'];
                	$customer 	    = $row['customerfname'].' '.$row['customersname'];
                	$saleType 	    = $row['saleType'];
                	$items 		     = $row['items'];
                	$amount 	     = $row['amount_total'];
                ?>
                <tr>
                    <td id="row_id"><?php echo $sale_id; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $saleby; ?></td>
                    <td><?php echo $customer; ?></td>
                    <td><?php echo $saleType; ?></td>
                    <td><?php echo $items; ?></td>
                    <td>K<?php echo number_format($amount); ?></td>
                    <td><a href="PDF/generator/customer-sales-report.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>&amp;saleType=<?php echo $saleType ?>&amp;customer_id=<?php echo $customer_id ?>" class="btn btn-info">Print All Sales</a></td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>

    <div style="margin-top: 20px;">

    </div>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>