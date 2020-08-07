<?php 
	header("Content-Type: application/json");
	require_once '../init.php';
	$results = $db->query("SELECT c_fname, c_lname, national_id FROM tbl_customer WHERE state = 'active'");
	$rows = $results->fetch_all(MYSQLI_ASSOC);
	$array = [];
	foreach ($rows as $row) {
		$fullname = $row['c_fname'].' '.$row['c_lname'].' -- (NAT ID:'.$row['national_id'].')';
		array_push($array, $fullname);
	}
	echo json_encode($array);
?>