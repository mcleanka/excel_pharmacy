<?php
	require_once '../init.php';
	$customerName = $_POST['customerName'];
	$cfname = substr($customerName, 0, strripos($customerName, " "));
	$clname = substr($customerName, strripos($customerName, " ")+1 ,strlen($customerName));
	$nationalID	= $_POST['nationalID'];

	$res=$db->query("SELECT * FROM tbl_customer WHERE national_id = '$nationalID' AND c_fname = '$cfname' AND c_lname = '$clname' AND state = 'ACTIVE'");
	if ($res->num_rows > 0) {
		// $array = [];
		while ($row = mysqli_fetch_assoc($res)) {
			$nationalID 	= $row['national_id'];
			$fullname 		= $row['c_fname'].' '.$row['c_lname'];
			$organization 	= $row['org_name']; 
			$customerID = $row['customer_id'];
		}
		echo $nationalID.'$'.$fullname.'*'.$organization.'&'.$customerID.'^';
	}
	else{
		echo "Customer Not Found";
	}
?>