<?php
ini_set("max_execution_time", 100000); 
require_once '../init.php';
$file=fopen("../Files/drugs.csv", "r");
while (!feof($file)) {
	$array=fgetcsv($file);
	$code=$array[0];
	$name=addslashes($array[1]);
	$ttype=str_replace(' ','',$array[2]);
	$price=str_replace(',', '', $array[3]);
	switch ($ttype) {
		case 'CBD':
			$t = 1;
			break;
		case 'CGD':
			$t = 2;
			break;
		case 'NBD':
			$t = 3;
			break;
		case 'NGD':
			$t = 4;
			break;
		case 'SPE':
			$t = 5;
			break;
		case 'OTC':
			$t = 6;
			break;
		case 'NPD':
			$t = 7;
			break;
		case 'VAC':
			$t = 8;
			break;
		case 'HIV/AIDS':
			$t = 9;
			break;		
		default:
			$t = 10;
			break;
	}
	$res = $db->query("SELECT * FROM tbl_drug WHERE name='$name'");
	if($res->num_rows == 0){
		$qry = "INSERT INTO tbl_drug (name,code,price,tariff,state) VALUES ('$name','$code',$price,$t,'active');";
		$db->query($qry) or die($qry);
	}
}
fclose($file);
echo "success";
?>
