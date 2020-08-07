<?php 
	require_once '../init.php';
	$customer_id 	= $_POST['customer_id'];
	$amount_total 	= $_POST['amount_total'];
	$saleType = $_POST['saleType'];

	$user_id = get_user_id();		
	$qry = "INSERT INTO tbl_sale (customer_id, user_id, amount_total, sale_type, sale_date, state) 
	VALUES('$customer_id','$user_id','$amount_total','$saleType', now(),'ACTIVE')";

	$db->query($qry);

	$res = $db->query("SELECT max(sale_id) AS high FROM tbl_sale WHERE customer_id = $customer_id") or die("ID Error");
	while ($row = mysqli_fetch_assoc($res)) {
		$num = $row['high'];
	}
	if($customer_id != '1'){
		$db->query("INSERT INTO tbl_masm_claim(sale_id, amount, added_on, state) VALUES($num, $amount_total, '$saleType', now());");
	}
	echo $num;
?>