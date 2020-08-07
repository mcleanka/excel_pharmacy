<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "inventory-purchases.php";
require_once 'Includes/templates/header.php';

if (!isset($_GET['id'])) {
	die("No Supplier Selected");
}
$id = $_GET['id'];
?>

<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
<div class="container" id="main-content">
    <div class="header">
        <label class="header-name">Supplier's Drugs</label>
        <span class="mr-0 pull-right">
            <?php include 'Includes/components/inventory-top-buttons.php'; ?>
        </span>
        <hr>
    </div>
<div class="row">
    <div class="col-xs-12">
        <div class="action-btn pb-3">

<div class="row">
	<div class="col-xs-12">
		<?php 
			$res = $db->query("SELECT * FROM tbl_supplier WHERE supplier_id=$id");
			while($row = mysqli_fetch_assoc($res)){
				$sname = $row['name'];
			}
		?>
		<h3 class="text-center" style="background-color: #009688; color: #FFF; padding: 5px 0px 5px 0px;">Drugs Supplied by <?php echo $sname; ?></h3>
		<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Unit Price</th>
                    <th>Product Type</th>
                    <th>VIP Discount </th>
                    <th>EXEC Discount</th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                    $res = $db->query("SELECT tbl_drug.* FROM tbl_drug, tbl_drug_supplier,tbl_supplier WHERE tbl_drug.drug_id=tbl_drug_supplier.drug_id AND tbl_supplier.supplier_id=tbl_drug_supplier.supplier_id AND tbl_supplier.supplier_id=$id ");
                    while ($row = mysqli_fetch_assoc($res)):
                    $dname = $row['name'];
                    $dcode = $row['code'];
                    $price = $row['price'];
                ?>
                <tr>
                    <td><?php echo $dname; ?></td>
                    <td><?php echo $dcode; ?></td>
                    <td>K<?php echo number_format($price) ?></td>
                    <td><?php echo $ttype; ?></td>
                    <td><?php echo $vip; ?>%</td>
                    <td><?php echo $exec; ?>%</td>
                </tr>
            <?php endwhile; ?>        
        </tbody>
    </table>
	</div>		
</div>

<?php require_once 'Includes/templates/footer.php'; ?>
