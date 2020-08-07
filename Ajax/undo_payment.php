<?php 
require_once '../init.php';
if(!isset($_POST['item'])){
	die("error");
}
$item = $_POST['item'];
if($db->query("DELETE FROM tbl_payment WHERE payment_id=$item")){
	echo "success";
}
else{
	echo "error";
}
?>