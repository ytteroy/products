<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('IN_WEB', true);
defined('IN_WEB') or die();

require 'config.php';
include INCLUDES_PATH . 'db.php';
include INCLUDES_PATH . 'page.php';
include INCLUDES_PATH . 'products.php';
include INCLUDES_PATH . 'excel.php';

if(!session_id()) {
	session_start();
}

$db = new db(DB['host'], DB['user'], DB['pass'], DB['base']);
$page = new page();
$products = new products();
$excel = new excel();

if(isset($_POST['importexcel'])){
	$excel->setFile($_FILES['excelfile']);
	$excel->import();
	header('Location: ' . SITE_URL);
}

if(isset($_POST['add'])){
	$add = $products->add($_POST);
	
	if($add == 'added'){
		header('Location: ' . $siteurl);
	}
}