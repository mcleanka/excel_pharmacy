<?php
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$pageTitle = 'INVOICES';
$current_page = "invoices.php";
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
                swal("Invalid Selection", "Please Select a Sale Record", "error");
            }
            else{
                window.location.href='sale-details.php?id=' + drug;
            }
        });

        $("#download_row").click(function(){
            drug = $(".selected").find("#row_id").text();
            if(drug==''){
                swal("Invalid Selection", "Please Select a Sale Record", "error");
            }
            else{
                
                
                window.location.href='PDF/generator/receipt.php?id=' + drug;
            }
        });
   });
</script>
<div class="container" id="main-content">
<div class="header">
    <label class="header-name">INVOICES: <?php echo get_month_name($month).', '.$year ?></label>
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
        <div class="action-btn mb-2 mt-0">
            <a href="PDF/generator/sales-report.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>" class="btn btn-info">Print Invoices Report</a>
            <a href="PDF/generator/sales-report3.php?year=<?php echo $year ?>&amp;month=<?php echo $month ?>" class="btn btn-primary">Download Invoices Report</a>  
       </div>
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Surname</th>                    
                    <th>Contact</th>
                    <th>National ID</th>
                    <th>Organisation</th>
                    <th>Print Invoice</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                    $res = $db->query("SELECT * FROM tbl_customer WHERE state='active'");
                    while ($row = mysqli_fetch_assoc($res)):
                    $customer_id = $row['customer_id'];
                    $firstname = $row['c_fname'];
                    $surname = $row['c_lname'];
                    $phone = $row['phone_number'];
                    $natID = $row['national_id'];
                    $org = $row['org_name'];
                ?>
                <tr>
                    <td id="row_id"><?php echo $customer_id; ?></td>
                    <td><?php echo $firstname; ?></td>
                    <td><?php echo $surname; ?></td>
                    <td><?php echo $phone; ?></td>
                    <td><?php echo $natID; ?></td>
                    <td><?php echo $org; ?></td>
                    <td class="text-center"><a href="PDF/generator/monthly-invoice.php?id=<?php echo $customer_id ?>&amp;year=<?php echo $year ?>&amp;month=<?php echo $month ?>"><i class="fa fa-print"></i> Print</a></td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>
</div>
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
