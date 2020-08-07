<?php
	require_once '../init.php';
	$tariff = $_GET['tariff'];
	$scheme = $_GET['scheme'];

	$res = $db->query("SELECT * FROM tbl_tariff WHERE tariff_id='$tariff'");
	while ($row = mysqli_fetch_assoc($res)) {
		$vip = $row['vip'];
		$exec = $row['exec'];
		$eco = $row['eco'];
	}

	switch ($scheme) {
		case 'VIP':
			echo $vip;
			break;
		case 'EXECUTIVE':
			echo $exec;
			break;
		case 'ECONOPLAN':
			echo $eco;
			break;
		case '':
			echo $exec;
			break;
		
		default:
			echo $exec;
			break;
	}
?>