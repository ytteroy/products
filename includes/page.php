<?php
class Page{
	private $site_title = '';
	
	public function set_page_title($value){
		$this->site_title = $value;
	}
	
	public function get_page_title(){
		return $this->site_title;
	}
}