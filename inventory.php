<?php 
require_once 'init.php';
$pageTitle = 'INVENTORY';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory.php";
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
<div class="container" id="main-content">
<div class="header">
  <label class="header-name">Inventory List</label>
    <?php if(get_user_type()=='admin' || get_user_type()=='Admin' || get_user_type()=='ADMIN'): ?> 
        <label class="header-name pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </label>
    <?php else: ?>
        <label class="header-name pull-right">
            <span id="Time"></span>
        </label>
    <?php endif; ?>
  <hr>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn mb-3 ">
        	<button id="info_row" style="width:150px;" class="btn btn-info">Transfer Inventory</button>
        	<a href="PDF/generator/stock-report.php<?php if(isset($_GET['all'])) { echo "?all=on"; } ?>" class="btn btn-warning pl-1">Stock Report PDF</a>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#cb").change(function(){
						var status = $("#cb").val();
						if ($("#cb").is(":checked")) {
							window.location.href='inventory.php?all=on';
						}
						else{
							window.location.href='inventory.php';
						}
					});
				});
			</script>
        </div>

        <div>
        
        <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                	<th>Stock ID</th>
                    <th>Drug Name</th>
                    <th>Drug Code</th> 
                    <th>Shop</th>
                    <th>Quantity</th>
                    <th>Last Restock</th>
                    <th>Action</th>
            </thead>

            <tbody>

            <?php 
            	if(isset($_GET['all'])){
            		$qry = "SELECT tbl_drug.*, tbl_shop.name AS shopname,tbl_stock.stock_id,tbl_stock.quantity, tbl_stock.last_updated FROM tbl_drug,tbl_shop,tbl_stock WHERE tbl_stock.drug_id=tbl_drug.drug_id AND tbl_stock.shop_id=tbl_shop.shop_id AND tbl_stock.quantity>0";
    				$res = $db->query($qry);            		
	           	}
            	else{
            		$sid = get_shop_id();
            		$qry = "SELECT tbl_drug.*,tbl_shop.name AS shopname,tbl_stock.stock_id,tbl_stock.quantity, tbl_stock.last_updated FROM tbl_drug,tbl_shop,tbl_stock WHERE tbl_stock.drug_id=tbl_drug.drug_id AND tbl_stock.shop_id=tbl_shop.shop_id AND tbl_stock.quantity>0 AND tbl_stock.shop_id=$sid";
					$res = $db->query($qry);            		
            	}

				while ($row = mysqli_fetch_assoc($res)):
				$stockid = $row['stock_id'];
				$dname = $row['name']; 
				$dcode = $row['code'];
                $drug_id = $row['drug_id'];
				$shop = $row['shopname'];
				$quantity = $row['quantity'];
				$date = date('jS F, Y', strtotime($row['last_updated']));
                $expDetails = array('drug_id' => $drug_id, 'drug_name' => $dname, 'shop_id' => $sid, 'shop_name' => $shop);
                $expiry_date_action = "<a href='#' id='".json_encode($expDetails)."' onclick='showExpiryModal(this.id)'> Expiry Date(s)</a>";
            ?>
            <tr>
                <td id="row_id"><?php echo $stockid; ?></td>
                <td><?php echo $dname; ?></td>
                <td><?php echo $dcode; ?></td>
                <td style="text-transform: uppercase;"><?php echo $shop; ?></td>
                <td><?php echo number_format($quantity); ?></td>
                <td><?php echo $date; ?></td>
                 <td><?php echo $expiry_date_action; ?></td>
            </tr>

        	<?php endwhile; ?>

            </tbody>
	    </table>

    	    <div style="margin-top: 200px;">
                <div id="showModal">

                </div>
            </div>
	    </div>
	</div>
</div>
<script type="text/javascript">
    function showExpiryModal (obj) {
        var expDetails = obj;
        expDetails = JSON.parse(expDetails);
        $(document).ready(function(){
        $.ajax({
          url: 'Ajax/expiry.php', 
          dataType: 'html',  
          data: {expDetails: expDetails},
          type: 'post',
          success: function(res){
            $("#showModal").html(res);
            $('#expiry').appendTo("body").modal('show');
          }
        });
      });
}
</script>
<?php require_once 'Includes/templates/footer.php'; ?>
