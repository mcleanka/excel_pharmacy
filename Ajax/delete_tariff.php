<?php 
require_once '../init.php';
if(!isset($_POST['tariff'])){
	die("error");
}
$tariff = $_POST['tariff'];
if($db->query("UPDATE tbl_tariff SET state='deleted' WHERE tariff_id=$tariff")){
	echo "success";
}
else{
	echo "error";
}
?>