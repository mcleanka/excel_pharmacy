<?php 
	require_once '../init.php';
	$drug_id 		= $_POST['drug_id'];
	$quotation_id 	= $_POST['quotation_id'];
	$quantity 		= $_POST['quantity'];

	$db->query("INSERT INTO tbl_quotation_items(quotation_id, drug_id, quantity)
		VALUES($quotation_id, $drug_id, '$quantity')") or die("Error");

	echo "success";
?>