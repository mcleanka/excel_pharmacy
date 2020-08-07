<?php
	if (!is_logged_in()) {
		header("Location:login.php");
	}
	$title = isset($pageTitle) ? $pageTitle.' | EXCEL PHARMACY' : 'EXCEL PHARMACY';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mclean Kasambala">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" media="all">
  <link rel="stylesheet" type="text/css" href="assets/fontawesome-free/css/all.min.css" media="all">
  <link rel="stylesheet" type="text/css" href="assets/datatables/dataTables.bootstrap4.css" media="all">
  <link rel="stylesheet" type="text/css" href="assets/css/sb-admin.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/flexselect.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/autocomplete.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/select2.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css" media="all">
  <link rel="stylesheet" type="text/css" href="assets/css/custom.css" media="all">
	<link rel="stylesheet" type="text/css" href="assets/DT/css/jquery.dataTables.min.css" media="all">
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/custom.js"></script>
	<script src="assets/js/jquery-ui.js"></script>
	<script src="assets/js/flexselect.js"></script>

	<script src="assets/js/autocomplete.js"></script>

	<script src="assets/js/select2.js"></script>

	<script src="assets/js/liquidmetal.js"></script>
	<script src="assets/js/sweetalert.min.js"></script>
	<script src="assets/js/highcharts.js"></script>
	<script src="assets/DT/js/jquery.dataTables.min.js"></script>
  <script src="assets/chart.js/Chart.min.js"></script>

  <script>
    <?php
      $url = "li > a[href='$current_page']";
    ?>
    $(document).ready(function(){
      $("<?php echo $url ?>").css('background-color','#28A;');
      $("<?php echo $url ?>").css('border-left','10px orange solid');
    });
  </script>
</head>
<body id="page-top">
    <nav class="navbar navbar-dark fixed-top pt-0 mr-0 pr-0" style="background-color: #28A745;">
      <a class="navbar-brand mr-1 ml-0 pl-0" href="." style="font-size: 1.6em; font-weight: 300; color: #FFFFFF !important; text-transform: uppercase;"><?php echo $current_shop; ?></a>
        <img src="assets/images/icons/menu18.png" style="width: 20px; height: 20px; ">
      <div class="d-md-inline-block ml-auto mr-0 my-2 my-md-0">
          <span class="main-logo">
             <img src="assets/images/pharma_logo.jpg" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="30" height="30">
          </span>
      </div>
      <div class="d-md-inline-block ml-0 mr-md-3 my-2 my-md-0">
          <span class="header-title text-white">EXCEL PHARMACY</span>
      </div>
      <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0" style="font-size: 1.3em;">
        <li class="nav-item">
          <span class="nav-link mr-0">
            <img src="assets/images/icons/account18.png" style="width: 28px; height: 28px;">
            <?php echo get_user_name(); ?>
            <a  href="logout.php" class="text-white">
              [Logout]
            </a>
          </span>
        </li>
      </ul>
    </nav>
    <div id="wrapper">
      <ul class="sidebar navbar-nav" style="position: fixed; color: #FFF !important; margin-top: 60px; margin-bottom: -100% !important;">
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fa fa-home sidebar-icon"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="new-sale.php"><i class="fa fa-file sidebar-icon"></i> New Sale</a></li>
            <li class="nav-item"><a class="nav-link" href="sales.php"><i class="fa fa-money sidebar-icon"></i> Sales History</a></li>
            <li class="nav-item"><a class="nav-link" href="customers.php"><i class="fa fa-users sidebar-icon"></i> Customers</a></li>
            <li class="nav-item"><a class="nav-link" href="drugs.php"><i class="fa fa-plus-square sidebar-icon"></i> Products</a></li>
            <li class="nav-item"><a class="nav-link" href="invoices.php"><i class="fa fa-print sidebar-icon"></i> Invoices</a></li>
            <li class="nav-item"><a class="nav-link" href="inventory.php"><i class="fa fa-database sidebar-icon"></i> Inventory</a></li>
            <li class="nav-item"><a class="nav-link" href="quotations.php"><i class="fa fa-shopping-cart sidebar-icon"></i> New Quotation</a></li>
            <li class="nav-item"><a class="nav-link" href="all-quotations.php"><i class="fa fa-briefcase sidebar-icon"></i> Quotations</a></li>
            <?php if(get_user_type()=='admin' || get_user_type()=='Admin' || get_user_type()=='ADMIN'): ?>
            <li class="nav-item"><a class="nav-link" href="inventory-purchases.php"><i class="fa fa-calculator sidebar-icon"></i> Procurement</a></li>
            <li class="nav-item"><a class="nav-link" href="statistics.php"><i class="fa fa-bar-chart sidebar-icon"></i> Statistics</a></li>
            <li class="nav-item"><a class="nav-link" href="users.php"><i class="fa fa-user sidebar-icon"></i> System Users</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="password.php"><i class="fa fa-cog sidebar-icon"></i> Change Password</a></li>
      </ul>
      <div id="content-wrapper">
