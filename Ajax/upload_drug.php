<?php
ini_set("max_execution_time", 100000); 
require_once '../init.php';
$file=fopen("../Files/july2018.csv", "r");
while (!feof($file)) {
	$array=fgetcsv($file);
	$code=$array[0];
	$name=addslashes($array[1]);
	$price=str_replace(',', '', $array[3]);
	$res = $db->query("SELECT * FROM tbl_drug WHERE name='$name'");
	if($res->num_rows == 0){
		$qry = "INSERT INTO tbl_drug (name,code,price,state,quantity) VALUES ('$name','$code',$price,'active',99999999999);";
		$db->query($qry) or die($qry);
	}
}
fclose($file);
echo "success";
?>
