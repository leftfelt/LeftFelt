<?php

require_once dirname(__FILE__).'/BaseRenderer.class.php';

//管理ツール用のレンダラー

class ManageRenderer extends BaseRenderer
{
	public function __construct(&$controller){
		$this->setBaseTemplate('common/base/base.html');
		parent::__construct($controller);
	}

	//共通で読み込ませるJSファイル
	public function getCommonJsList(){
		$common_js_list = array();
		//$common_js_list[] = 'common/js/lib/jquery-1.8.2.js';
		$common_js_list[] = 'common/js/lib/prototype-1.7.1.js';
		return $common_js_list;
	}

	//共通で読み込ませるCSSファイル
	public function getCommonCssList(){
		$common_css_list = array();
		return $common_css_list;
	}
}
