<?php 
require_once 'init.php';
$pageTitle = 'INVENTORY';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory.php";
require_once 'Includes/templates/header.php';

$year   = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month  = isset($_GET['month']) ? $_GET['month'] : str_replace("0", "", date('m'));
$day  = isset($_GET['day']) ? $_GET['day'] : str_replace("0", "", date('d'));
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

        $("#info_row").click(function(){
            item = $(".selected").find("#row_id").text();
            if(item==''){
                swal("Invalid Selection", "Please Select a Stock to Transfer", "error");
            }
            else{
                window.location.href='inventory-transfer.php?id=' + item;
            }
        });
    
    });
</script>
<div class="row">
    <div class="col-xs-8">
        <h2 class="module-header">Products Expiring List</h2>
    </div>

    <div class="col-xs-4" style="margin-top: 20px;">
        <?php include 'Includes/components/inventory-top-buttons.php'; ?>
    </div>
</div>

<legend style="margin-top: 10px;"></legend>

<div class="row" style="margin-top: 20px;">

        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                	<th>Stock ID</th>
                    <th>Product Name</th>
                    <th>Product Code</th> 
                    <th>Shop</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
            </thead>

            <tbody>

            <?php 
            	if($day > 14){
            		$sid = get_shop_id();
                    $qry = "SELECT tbl_drug.*, tbl_shop.name AS shopname, tbl_stock.stock_id, tbl_stock.expiry_date, tbl_stock.quantity, tbl_stock.last_updated FROM tbl_drug,tbl_shop,tbl_stock WHERE tbl_stock.drug_id = tbl_drug.drug_id AND tbl_stock.shop_id = tbl_shop.shop_id AND tbl_stock.quantity > 0 AND tbl_stock.shop_id = $sid AND YEAR(tbl_stock.expiry_date)='$year' AND MONTH(tbl_stock.expiry_date)= '$month' AND DAY(tbl_stock.expiry_date) > 14";
                    $res = $db->query($qry);            		
	           	}
            	else{
            		$sid = get_shop_id();
            		$qry = "SELECT tbl_drug.*, tbl_shop.name AS shopname, tbl_stock.stock_id, tbl_stock.expiry_date, tbl_stock.quantity, tbl_stock.last_updated FROM tbl_drug,tbl_shop,tbl_stock WHERE tbl_stock.drug_id = tbl_drug.drug_id AND tbl_stock.shop_id = tbl_shop.shop_id AND tbl_stock.quantity > 0 AND tbl_stock.shop_id = $sid AND YEAR(tbl_stock.expiry_date)='$year' AND MONTH(tbl_stock.expiry_date)= '$month' AND DAY(tbl_stock.expiry_date) < 14";
					$res = $db->query($qry);            		
            	}

				while ($row = mysqli_fetch_assoc($res)):
				$stockid = $row['stock_id'];
				$dname = $row['name']; 
				$dcode = $row['code'];
                $drug_id = $row['drug_id'];
				$shop = $row['shopname'];
				$quantity = $row['quantity'];
				$expiry_date = date('jS F, Y', strtotime($row['expiry_date']));
            ?>
            <tr>
                <td id="row_id"><?php echo $stockid; ?></td>
                <td><?php echo $dname; ?></td>
                <td><?php echo $dcode; ?></td>
                <td style="text-transform: uppercase;"><?php echo $shop; ?></td>
                <td><?php echo number_format($quantity); ?></td>
                <td><?php echo $expiry_date; ?></td>
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>
	</div>
</div>

<!-- for showing modal content -->
<?php require_once 'Includes/templates/footer.php'; ?>
