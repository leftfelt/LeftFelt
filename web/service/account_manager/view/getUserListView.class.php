<?php

require_once dirname(__FILE__)."/../config.inc.php";

class getUserListView extends View{
	public function initialize(){
	}
	public function execute(){
		$renderer = $this->getRenderer('Json');
		$user_list = $this->request->getAttribute("user_list");
		$renderer->setAttribute("user_list",$user_list->toArray());
		return $renderer;
	}
}
