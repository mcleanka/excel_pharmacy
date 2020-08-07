<?php 
	require_once '../init.php';
	if (empty($_GET['drugname'])) {
		echo json_encode([]);
		exit();
	}
	$dquantity = $_GET['dquantity'];
	$dname = mysqli_real_escape_string($db,$_GET['drugname']);
	$results = $db->query("SELECT * FROM tbl_drug WHERE name='$dname' LIMIT 1");
	$rows = $results->fetch_all(MYSQLI_ASSOC);
	$drug_id = $rows[0]['drug_id'];

	$shop = get_shop_id();
	$qry = "SELECT * FROM tbl_stock WHERE drug_id = $drug_id";
	$res = $db->query($qry) or die($qry);
	if($res->num_rows == 0){
		die("unvailable");
		exit();
	}
	while($row = mysqli_fetch_assoc($res)){
		$available_qty = $row['quantity'];
	}
	if ($available_qty < $dquantity) {
		die("shortage".$available_qty);
		exit();
	}

	echo json_encode($rows, JSON_PRETTY_PRINT);
?>