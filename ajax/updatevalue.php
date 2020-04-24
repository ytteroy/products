<?php
include '../init.php';

$id = (int)$_POST['id'];

if($id){
	echo $products->edit($id, $_POST);
}