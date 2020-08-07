<?php 
	require_once '../init.php';
	if (empty($_GET['customerName'])) {
		echo json_encode([]);
		exit();
	}
	$customerName 	= mysqli_real_escape_string($db, $_GET['customerName']);
	$organisation 	= mysqli_real_escape_string($db, $_GET['organisation']);
	$location 		= mysqli_real_escape_string($db, $_GET['location']);
	$phone			= mysqli_real_escape_string($db, $_GET['phone']);
	$email			= mysqli_real_escape_string($db, $_GET['email']);
	$address		= mysqli_real_escape_string($db, $_GET['address']);
	$date 			= mysqli_real_escape_string($db, $_GET['date']);

	$db->query("INSERT INTO tbl_q_customer(full_name, organisation, location, phone, email, address, state, q_date)
		VALUES('$customerName', '$organisation', '$location', '$phone', '$email', '$address', 'ACTIVE', 'now()');") or die("Insert Error");

	$results = $db->query("SELECT * FROM tbl_q_customer WHERE full_name = '$customerName' AND organisation = '$organisation' LIMIT 1") or die('Fetch Error');
	$rows = $results->fetch_all(MYSQLI_ASSOC);

	echo json_encode($rows, JSON_PRETTY_PRINT);
?>