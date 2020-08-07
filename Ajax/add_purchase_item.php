<?php 
	require_once '../init.php';
	$drug_id 		  = $_POST['drug_id'];
	$purchase_id 	= $_POST['purchase_id'];
	$unit_price 	= $_POST['unit_price'];
	$quantity 		= $_POST['quantity'];
	$supplier_id 	= $_POST['supplier_id'];
	$expiryDate   = $_POST['expDate'];
	$shop_id = get_shop_id();
	$res = $db->query("SELECT * FROM tbl_drug_supplier WHERE drug_id=$drug_id AND supplier_id=$supplier_id");
	if($res->num_rows == 0){
		$db->query("INSERT INTO tbl_drug_supplier (drug_id,supplier_id) VALUES($drug_id,$supplier_id)");
	}

	$qry = "INSERT INTO tbl_purchase_item (drug_id,purchase_id,quantity,unit_price,expiry_date) VALUES ('$drug_id','$purchase_id',$quantity,$unit_price,'$expiryDate');";
	$db->query($qry) or die($qry);

	$res=$db->query("SELECT * FROM tbl_stock WHERE drug_id=$drug_id");

	if($res->num_rows > 0){
		//update
		$db->query("UPDATE tbl_stock SET quantity=quantity+$quantity WHERE drug_id=$drug_id;") or die("update error");
	}
	else{
		//insert
		$db->query("
		INSERT INTO tbl_stock 
		(drug_id,shop_id,quantity,last_updated,state,expiry_date) VALUES
		($drug_id,$shop_id,$quantity,now(),'ACTIVE','$expiryDate')") or die("insert error");
	}

	echo "success";
