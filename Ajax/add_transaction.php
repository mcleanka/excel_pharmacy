<?php 
	require_once '../init.php';
	$drug_id 	= $_POST['drug_id'];
	$quantity 	= $_POST['quantity'];

	// Find a way how to get purchase_id before uncommenting
	// $purchase_id 	= $_POST['purchase_id'];
	// $qry = "SELECT * FROM tbl_purchase_item WHERE purchase_id = $purchase_id LIMIT 1";
	// $res = $db->query($qry);
	// while ($row = mysqli_fetch_assoc($res)){
	// 	$qty = $row['quantity'];
	// 	if ($qty < $quantity) {
	// 		$db->query("DELETE FROM tbl_purchase_item WHERE tbl_purchase_item.purchase_id = $purchase_id LIMIT 1");
	// 	}
	// }
	// $qty = $qty+$qty;

	$db->query("UPDATE tbl_stock SET quantity = quantity-$quantity WHERE drug_id = $drug_id");

	echo "success";
?>