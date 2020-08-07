<?php 
	require_once '../init.php';
	$purchasetype 	= $_POST['purchasetype'];
	$purchasedate 	= str_replace("/", "-", $_POST['purchasedate']);
	$supplier		= $_POST['supplier'];
	$total 			= $_POST['total'];
  // $expiry_date    = str_replace("/", "-", $_POST['expDate']);
	$db->query("INSERT INTO tbl_purchase (supplier_id,purchase_type,purchase_date,amount_total,state) VALUES('$supplier','$purchasetype','$purchasedate','$total','UNPAID')") or die("Failed");

	$res=$db->query("SELECT max(purchase_id) as high FROM tbl_purchase") or die("ID Error");
	while ($row=mysqli_fetch_assoc($res)) {
		$num = $row['high'];
	}
	echo $num;
?>