<?php 
require_once '../init.php';
if(!isset($_POST['item'])){
	die("error");
}
$item = $_POST['item'];
if($db->query("UPDATE tbl_supplier SET state='deleted' WHERE supplier_id=$item")){
	echo "success";
}
else{
	echo "error";
}
?>