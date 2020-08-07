<?php 
	require_once '../init.php';
	$customer_id 	= $_POST['cus_id'];
	$amount_total 	= $_POST['amount_total'];

	$user_id = get_user_id();		
	$qry = "INSERT INTO tbl_quotation (customer_id, user_id, amount_total, quotation_date, state) 
	VALUES('$customer_id','$user_id','$amount_total', now(), 'ACTIVE')";

	$db->query($qry);

	$res = $db->query("SELECT max(quotation_id) AS high FROM tbl_quotation WHERE customer_id = $customer_id") or die("ID Error");
	while ($row = mysqli_fetch_assoc($res)) {
		$num = $row['high'];
	}

	echo $num;
?>