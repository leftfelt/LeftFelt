<?php

require_once dirname(__FILE__)."/smarty/Smarty.class.php";

class FSmarty extends Smarty {
	public function __construct($base_dir){
		$this->template_dir = $base_dir.'/templates/';
		$this->compile_dir = $base_dir.'/temp/templates_c/';
		parent::__construct();
	}
}
