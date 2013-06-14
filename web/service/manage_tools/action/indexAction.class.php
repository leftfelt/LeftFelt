<?php

require_once('../config.inc.php');

class indexAction extends Action{
	public function getPreFilter(){
		return array('loadUser');
	}
	public function initialize(){
	}
	public function execute(){
	}
}
