<?php 
require_once 'app/init.php';
$code 	= $_GET['code'];
$name 	= $_GET['name'];
$price 	= $_GET['price'];
$ttype 	= $_GET['ttype'];

$qry = "INSERT INTO tbl_drug (name,code,price,tariff) VALUES ('$name','$code',$price,$ttype);";
$db->query($qry) or die($qry);

echo "Success";
?>