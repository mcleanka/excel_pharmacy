<?php 
require_once '../init.php';
if(!isset($_POST['item'])){
	die("error");
}
$item = $_POST['item'];
$password = md5("123456");
if($db->query("UPDATE tbl_user SET password='$password' WHERE user_id=$item")){
	echo "success";
}
else{
	echo "error";
}
?>