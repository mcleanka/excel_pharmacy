<?php 
	require_once '../init.php';
	if (!isset($_GET['drugname'])) {
		echo json_encode([]);
		exit();
	}
	$dn=mysqli_real_escape_string($db,$_GET['drugname']);
	$results=$db->query("SELECT * FROM tbl_drug WHERE name='$dn' LIMIT 1");
	$rows=$results->fetch_all(MYSQLI_ASSOC);
	echo json_encode($rows, JSON_PRETTY_PRINT);
?>