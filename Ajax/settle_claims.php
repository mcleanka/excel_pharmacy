<?php 
	require_once '../init.php';
	$year 	= $_POST['year'];
	$month 	= $_POST['month'];

	if($db->query("UPDATE tbl_masm_claim SET state='PAID' WHERE YEAR(added_on)='$year' AND MONTH(added_on)='$month'")){
		echo "success";
	}
?>