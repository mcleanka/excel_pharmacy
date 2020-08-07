<?php 
require_once 'init.php';
$current_shop = get_shop_name().' Shop';
$current_page = "dashboard.php";

$year   = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month  = isset($_GET['month']) ? $_GET['month'] : str_replace("0", "", date('m'));
$day  = isset($_GET['day']) ? $_GET['day'] : str_replace("0", "", date('d'));
$sid = get_shop_id();
require_once 'Includes/templates/header.php';
    $date = date('Y-m-d');
    $dateToday = date('d M, Y');

    if ($day < 14) {
        $qry="SELECT * FROM tbl_stock WHERE YEAR(tbl_stock.expiry_date)='$year' AND MONTH(tbl_stock.expiry_date)= '$month' AND DAY(tbl_stock.expiry_date) < 14 AND tbl_stock.shop_id = $sid AND tbl_stock.quantity > 0;"; 
        $result=$db->query($qry);
        $num_rows = mysqli_num_rows($result);
    } else {
        $qry="SELECT * FROM tbl_stock WHERE YEAR(tbl_stock.expiry_date)='$year' AND MONTH(tbl_stock.expiry_date)= '$month' AND DAY(tbl_stock.expiry_date) > 14 AND tbl_stock.shop_id = $sid AND tbl_stock.quantity > 0;"; 
        $result=$db->query($qry);
        $num_rows = mysqli_num_rows($result);
    }
    
 
    $res=$db->query("SELECT * FROM tbl_sale WHERE YEAR(tbl_sale.sale_date)='$year' AND MONTH(tbl_sale.sale_date)= '$month';");
    $sales_num_rows = mysqli_num_rows($res);

    $_res=$db->query("SELECT * FROM tbl_stock");
    $stock_num_rows = mysqli_num_rows($_res);

    $pun_res=$db->query("SELECT * FROM tbl_purchase WHERE YEAR(tbl_purchase.purchase_date)='$year' AND MONTH(tbl_purchase.purchase_date)= '$month';");
    $pun_rows = mysqli_num_rows($pun_res);
?>

<script type="text/javascript">
	$(document).ready(function(){
    
	});
</script>
<div class="container" id="main-content">
<div class="header">
  <label class="header-name">Dashboard</label>
  <label class="header-name pull-right"><span id='Time'> </span></label>&nbsp;
  <hr>
</div>
          <div>
            <div class="row">

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-default o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="badge badge-primary"><?php echo $num_rows; ?></i>
                  </div>
                  <div class="mr-5">Products Expiring in 2weeks</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="expiry-drugs.php">
                  <span class="float-left">View Details</span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-default o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="badge badge-primary"><?php echo $sales_num_rows; ?></i>
                  </div>
                  <div class="mr-5">Total Sales</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="sales.php">
                  <span class="float-left">View Details</span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-default o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="badge badge-primary"><?php echo $stock_num_rows; ?></i>
                  </div>
                  <div class="mr-5">Invoices</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="invoices.php">
                  <span class="float-left">View Details</span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-default o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="badge badge-primary"><?php echo $pun_rows; ?></i>
                  </div>
                  <div class="mr-5">Monthly Purchases</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="inventory-purchases.php">
                  <span class="float-left">View Details</span>
                </a>
              </div>
            </div>
          </div>
        </div>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-chart-area"></i>
              Annual Sales History</div>
            <div class="card-body">
              <canvas id="myBarChart" width="100%" height="30"></canvas>
            </div>
            <div class="card-footer small text-muted">Year: <?php echo date('Y'); ?></div>
          </div>
        </div>
<?php require_once 'includes/components/chat.php'; ?>
<?php require_once 'Includes/templates/footer.php'; ?>
