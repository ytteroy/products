<?php
include '../init.php';

$id = (int)$_GET['id'];

if($id){
	$products->remove($id);
	header('Location: ' . $siteurl);
}