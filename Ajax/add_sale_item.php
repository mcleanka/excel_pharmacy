<?php 
	require_once '../init.php';
	$drug_id 	= $_POST['drug_id'];
	$sale_id 	= $_POST['sale_id'];
	$quantity 	= $_POST['quantity'];
	$saleType	= $_POST['saleType'];

	$db->query("INSERT INTO tbl_sale_drug (sale_id, drug_id, sale_type, quantity)
		VALUES($sale_id, $drug_id, '$saleType', '$quantity')") or die("Error");

	echo "success";
?>