<?php
define('APP_ROOT', __DIR__);
define('INC_ROOT', APP_ROOT.'/Includes');
define('BASE_URL','http://localhost/excel_pharmacy');
define('ASSET_ROOT', BASE_URL.'/Assets/');
define('FILES_ROOT', BASE_URL.'/Files/');
$db = new mysqli('localhost','root','','excel_pharmacy');

session_start(); 
require_once 'functions.php';
?>