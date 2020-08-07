<?php 
require_once '../init.php';
if(!isset($_POST['drug'])){
	die("error");
}
$drug = $_POST['drug'];
if($db->query("UPDATE tbl_drug SET state='deleted' WHERE drug_id=$drug")){
	echo "success";
}
else{
	echo "error";
}
?>