<?php

require_once dirname(__FILE__).'/SmartyRenderer.class.php';

//共通のHTML JS CSSを使う処理をしたい場合は、BaseRendererを継承したクラスを定義する

abstract class BaseRenderer extends SmartyRenderer
{
	const BASE_TEMPLATE='base_template';
	const BASE_JS_LIST='base_js_list';
	const BASE_CSS_LIST='base_css_list';

	private $js_list = array(); //ページごとのJSリスト
	private $css_list = array(); //ページごとのCSSリスト
	private $base_template = null; //共通処理が書かれているHTMLファイル

	public function __construct(&$controller){
		parent::__construct($controller);
	}
	
	public function execute(){
		$this->setAttribute(self::BASE_TEMPLATE,$this->template);
		//共通のファイルとページごとのファイルを渡す
		$this->setAttribute(self::BASE_JS_LIST,
			array_merge(
				$this->getCommonJsList(),
				$this->js_list
			)
		);
		$this->setAttribute(self::BASE_CSS_LIST,
			array_merge(
				$this->getCommonCssList(),
				$this->css_list
			)
		);
		if(is_null($this->base_template)) throw new Exception('base_templateが設定されていません。use setBaseTemplate');
		$this->template = $this->base_template;
		parent::execute();
	}

	//ページごとに読み込ませたいJSファイルリストを設定する
	public function setJsList($js_list=array()){
		if(is_array($js_list)){
			$this->js_list = $js_list;
		}else{
			throw new Exception('js_listが配列ではありません。');
		}
	}

	//ページごとに読み込ませたいCSSファイルリストを取得する
	public function setCssList($css_list=array()){
		if(is_array($css_list)){
			$this->css_list = $css_list;
		}else{
			throw new Exception('css_listが配列ではありません。');
		}
	}

	//共通処理が書かれているテンプレートファイルを設定する
	//＊継承したレンダラは必ず実行する
	protected function setBaseTemplate($template){
		$this->base_template = $template;
	}
	
	//共通で読み込ませたいJSファイルリストを取得する
	//@return array();
	abstract protected function getCommonJsList();

	//共通で読み込ませたいCSSファイルリストを取得する
	//@return array();
	abstract protected function getCommonCssList();

}
