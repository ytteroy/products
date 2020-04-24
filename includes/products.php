<?php
class products {
	private $list = [];
	
	public function list(){
		global $db, $siteurl;
		
		$list = [];
		$res = $db->query("SELECT `id`, `sku`, `name`, `price` FROM `products` ORDER BY `created` DESC, `id` DESC");
		while($row = $db->fetch($res)){
			$row['price'] = $row['price'] * 0.01;
			$row['remove'] = '<a href="'.$siteurl.'/ajax/removerow.php/?id='.$row['id'].'">Remove</a>';
			
			$list[] = $row;
		}
		
		return $list;
	}
	
	public function add($post){
		global $db;
		
		$formok = true;
		
		if(!isset($post['code']) || !isset($post['name']) || !isset($post['price'])){
			$formok = false;
		}
		
		if($formok){
			$insert = [
				'sku' => $post['code'],
				'name' => $post['name'],
				'price' => $post['price'] / 0.01,
				'created' => time()
			];
			
			if($db->insert_array('products', $insert)){
				return 'added';
			}else{
				return 'dberror';
			}
		}else{
			return 'checkform';
		}
	}
	
	public function edit($id, $data){
		global $db;
		
		$id = (int)$id;
		$data['col'] = htmlspecialchars($data['col']);
		$data['nextVal'] = htmlspecialchars($data['nextVal']);
		
		if(!$id){
			return;
		}
		
		if($data['col'] == 'price'){
			$data['nextVal'] = $data['nextVal'] / 0.01;
		}
		
		return $db->query("UPDATE `products` SET `".$data['col']."` = '".$data['nextVal']."' WHERE `id` = '".$id."'");
	}
	
	public function remove($id){
		global $db;
		
		$id = (int)$id;
		if(!$id){
			return;
		}
		
		return $db->query("DELETE FROM `products` WHERE `id` = '".$id."'");
	}
}