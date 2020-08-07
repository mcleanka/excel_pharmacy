<?php
	require '../../init.php';
	$year = $_GET['year'];
	$month = $_GET['month'];

	$data_array=[];

	$qry = "SELECT 
				tbl_sale.sale_id, 
				tbl_sale.sale_date, 
				tbl_user.first_name AS userfname, 
				tbl_user.last_name AS usersname,
				tbl_customer.c_fname AS customerfname, 
				tbl_customer.c_lname AS customersname,
			(SELECT count(*) FROM tbl_sale_drug WHERE tbl_sale_drug.sale_id = tbl_sale.sale_id) AS items,
			tbl_sale.amount_total, 
			tbl_sale.sale_type 
			FROM 
				tbl_sale, tbl_user, tbl_customer 
			WHERE tbl_sale.user_id = tbl_user.user_id 
			AND tbl_sale.customer_id = tbl_customer.customer_id 
			AND YEAR(tbl_sale.sale_date) = '$year' 
			AND MONTH(tbl_sale.sale_date) = '$month' 
			ORDER BY tbl_sale.sale_date DESC;";
			
	$res = $db->query($qry);

	while ($row = mysqli_fetch_assoc($res)){
		$date = $row['sale_date'];
		$customer = $row['customerfname'].' '.$row['customersname'];
		$amount_total = $row['amount_total'];
		$saleType = $row['sale_type'];
		$array = [$date, $customer, $saleType ,$amount_total];
		array_push($data_array, $array);
	}
	$qry = "SELECT sale_type, sum(amount_total) AS total FROM tbl_sale WHERE YEAR(sale_date) = '$year' AND MONTH(sale_date)='$month'";
	$res = $db->query($qry) or die($qry);
	while ($row = mysqli_fetch_assoc($res)) {
		$total = $row['total'];
		$salaType = $row['sale_type'];
	}
	//END MY SCRIPTS
	require_once('tcpdf_include.php');
?>