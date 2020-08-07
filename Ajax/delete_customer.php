<?php 
require_once '../init.php';
if(!isset($_POST['item'])){
	die("error");
}
$item = $_POST['item'];
if($db->query("UPDATE tbl_customer SET state='deleted' WHERE customer_id=$item")){
	echo "success";
}
else{
	echo "error";
}
?>