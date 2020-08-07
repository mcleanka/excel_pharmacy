<?php
function escape($x){
	global $db;
	$x = strip_tags($x);
	$x = stripslashes($x);
	return mysqli_real_escape_string($db,$x);
}

function login($username,$password){
	global $db;
	$username = escape($username);
	$password = md5($password);

	$res = $db->query("SELECT * FROM tbl_user WHERE username='$username' AND password='$password'");
	if($res->num_rows > 0){
		while ($row = mysqli_fetch_assoc($res)) {
			$user_id 		= $row['user_id'];
			$account_type 	= $row['account_type'];
			$shop 			= $row['shop'];
			$username 		= $row['username'];
			$password 		= $row['password'];
		}
		$_SESSION['citipharm']['user_id'] 		= $user_id;
		$_SESSION['citipharm']['account_type'] 	= $account_type;
		$_SESSION['citipharm']['shop'] 			= $shop;
		$_SESSION['citipharm']['username'] 		= $username;
		$_SESSION['citipharm']['password'] 		= $password;

		$db->query("UPDATE tbl_user SET last_login=now() WHERE user_id='$user_id';");

		return true;
	}
	return false;
}
function is_logged_in(){
	global $db;
	if(isset($_SESSION['citipharm'])){
		$username = $_SESSION['citipharm']['username'];
		$password = $_SESSION['citipharm']['password'];

		$res = $db->query("SELECT * FROM tbl_user WHERE username='$username' AND password='$password'");
		if($res->num_rows > 0){
			return true;
		}
	}
	return false;	
}

function redirect($x){
	header("Location: $x");
}
function js_redirect($x){
	echo "<script> window.location.href='".$x."'; </script>";
}

function get_shop_name(){
	global $db;
	$shop = $_SESSION['citipharm']['shop'];
	$res = $db->query("SELECT * FROM tbl_shop WHERE shop_id=$shop");
	while ($row = mysqli_fetch_assoc($res)) {
		$shopname = $row['name'];
	}
	return $shopname;
}
function get_user_type(){
	return $_SESSION['citipharm']['account_type'];
}
function get_user_id(){
	return $_SESSION['citipharm']['user_id'];
}
function get_user_name(){
	global $db;
	$user_id = $_SESSION['citipharm']['user_id'];
	$res = $db->query("SELECT * FROM tbl_user WHERE user_id=$user_id");
	while ($row = mysqli_fetch_assoc($res)) {
		$name = $row['first_name'].' '.$row['last_name'];
	}
	return $name;
}
function get_shop_id(){
	return $_SESSION['citipharm']['shop'];	
}
function get_month_name($month){
	switch ($month) {
		case '1':
			return "JANUARY";
			break;
		case '2':
			return "FEBRUARY";
			break;
		case '3':
			return "MARCH";
			break;
		case '4':
			return "APRIL";
			break;
		case '5':
			return "MAY";
			break;
		case '6':
			return "JUNE";
			break;
		case '7':
			return "JULY";
			break;
		case '8':
			return "AUGUST";
			break;
		case '9':
			return "SEPTEMBER";
			break;
		case '10':
			return "OCTOBER";
			break;
		case '11':
			return "NOVEMBER";
			break;
		case '12':
			return "DECEMBER";
			break;
		
		default:
			return false;
			break;
	}
}
function get_column_chart_data_masm($year,$month){
	global $db;
	$qry = "SELECT sum(amount_total) AS total FROM tbl_sale WHERE customer_id<>1 AND state='ACTIVE' AND YEAR(sale_date)='$year' AND MONTH(sale_date)='$month';";
	$res = $db->query($qry);
	while ($row = mysqli_fetch_assoc($res)) {
		$total = round($row['total']);
	}
	if($total!=null){
		return $total;
	}
	return "0";
}

function get_column_chart_data_non_masm($year,$month){
	global $db;
	$qry = "SELECT sum(amount_total) AS total FROM tbl_sale WHERE customer_id=1 AND state='ACTIVE' AND YEAR(sale_date)='$year' AND MONTH(sale_date)='$month';";
	$res = $db->query($qry);
	while ($row = mysqli_fetch_assoc($res)) {
		$total = round($row['total']);
	}
	if($total!=null){
		return $total;
	}
	return "0";
}

function get_line_chart_data($year, $month, $drug_id){
	global $db;
	$qry = "SELECT sum(tbl_sale_drug.quantity) AS figure FROM tbl_sale_drug, tbl_sale WHERE tbl_sale_drug.drug_id=$drug_id AND tbl_sale.sale_id=tbl_sale_drug.sale_id AND YEAR(tbl_sale.sale_date)='$year' AND MONTH(tbl_sale.sale_date)= '$month';";
	$res = $db->query($qry);
	while ($row = mysqli_fetch_assoc($res)) {
		$figure = $row['figure'];
	}
	if($figure==null){
		return 0;
	}
	return $figure;
}

function get_bar_chart_data($year, $month){
	global $db;
	$qry = "SELECT sum(tbl_sale_drug.quantity) AS figure FROM tbl_sale_drug, tbl_sale WHERE YEAR(tbl_sale.sale_date)='$year' AND MONTH(tbl_sale.sale_date)= '$month';";
	$res = $db->query($qry);
	while ($row = mysqli_fetch_assoc($res)) {
		$figure = $row['figure'];
	}
	if($figure==null){
		return 0;
	}
	return $figure;
}