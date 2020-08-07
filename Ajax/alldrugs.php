<?php 
	header("Content-Type: application/json");
	require_once '../init.php';
	$results=$db->query("SELECT name FROM tbl_drug WHERE state = 'active'");
	$rows=$results->fetch_all(MYSQLI_ASSOC);
	$array=[];
	foreach ($rows as $row) {
		array_push($array, $row['name']);
	}
	echo json_encode($array);
?>