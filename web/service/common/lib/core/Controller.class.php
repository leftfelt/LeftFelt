<?php
require_once dirname(__FILE__)."/Logger.class.php";
require_once dirname(__FILE__)."/Request.class.php";
require_once dirname(__FILE__)."/Renderer.class.php";
require_once dirname(__FILE__)."/Model.class.php";
require_once dirname(__FILE__)."/Logic.class.php";
require_once dirname(__FILE__)."/Filter.class.php";
require_once dirname(__FILE__)."/Action.class.php";
require_once dirname(__FILE__)."/View.class.php";
require_once dirname(__FILE__)."/Cache.class.php";

//コントローラ

class Controller{
	
	public $request;
	public $config;
	protected $error_log;

	public function __construct($config=array()){
		$this->request = new Request;
		$this->config = $config;
		$this->error_log = new Logger(get_class($this));
	}

	//指定したモデルのインスタンスを生成する	
	public function getModel($class_name){
		return $this->getLibraryClass("model", $class_name."Model");
	}

	//指定したレンダラのインスタンスを生成する
	public function getRenderer($class_name){
		$renderer = $this->getLibraryClass("renderer", $class_name."Renderer");
		return $renderer;
	}
	//Logicの取得
	public function getLogic($class_name){
		$logic = $this->getLibraryClass("logic", $class_name."Logic");
		return $logic;
	}
	
	//Componentの読み込み
	public function loadComponent($class_name){
		$this->loadClass(
				$this->config['common_library_dir'],
				$this->config['library_dir'],
				"component",
				$class_name
			);
	}

	//Filterの取得
	private function getFilter($class_name){
		$filter = $this->getLibraryClass("filter", $class_name."Filter");
		return $filter;
	}

	//Actionの取得
	private function getAction($class_name){
		$action = $this->getBaseClass("action",$class_name."Action");
		return $action;
	}
	//Viewの取得
	private function getView($class_name){
		$view = $this->getBaseClass("view",$class_name."View");
		return $view;
	}
	
	//実装は継承先に任せる
	//使用するRendererを返す(返さなかったらRendererスルー)
	protected function execute(){
	}

	//実行する
	public function dispatch($class_name=""){
		if($class_name!==""){
			$action = $this->getAction($class_name);
			//前処理
			$this->executeFilters(
				$action->getPreFilter()
			);
			//メイン処理
			//前処理によってはスキップとかするかも
			$view_name = $action->execute();
			//後処理
			$this->executeFilters(
				$action->getPostFilter()
			);
			
			if(!$view_name || $view_name === ""){
				//actionが指定したviewを実行
				$view_name = $class_name;
			}
			$view = $this->getView($view_name);
			$renderer = $view->execute();
		}else{
			//todo:今のところはControllerだけでも動くようになってるけど
			//todo:そのうちActionとView必須にする
			$renderer = $this->execute();
		}
		if($renderer && $renderer instanceof Renderer){
			$renderer->execute();
		}else{
			throw new Exception("[core] [".get_class($this)."]でRendererが指定されませんでした。");
		}
	}

	/**
	 * Filterを実行する
	 *@param array $filter_name_list 実行するフィルタの名前リスト
	 */
	private function executeFilters(array $filter_name_list){
		foreach($filter_name_list as $filter_name){
			$filter = $this->getFilter($filter_name);
			$filter->execute();//いったん実行するだけ
		}
	}

	/**
	 * ライブラリ内クラスのインスタンスを生成する
	 *@param dir_name このファイルと同じフォルダ内にあるフォルダ名
	 *@param class_name クラス名
	 *@return 指定したクラス名のインスタンス
	 */
	private function getLibraryClass($dir_name, $class_name){
	
		$this->loadClass(
				$this->config['common_library_dir'],
				$this->config['library_dir'],
				$dir_name,
				$class_name
			);
		
		return new $class_name($this);
	}

	private function loadClass($default_dir,$base_dir,$dir_name,$class_name){
		$class_file = $this->getDirectoryClassFileName(
				$default_dir,
				$base_dir,
				$dir_name,
				$class_name
			);
		require_once $class_file;
	}

	/**
	 * ディレクトリのクラスのインスタンスを生成する
	 *@param dir_name このファイルと同じフォルダ内にあるフォルダ名
	 *@param class_name クラス名
	 *@return 指定したクラス名のインスタンス
	 */
	private function getBaseClass($dir_name, $class_name){

		$this->loadClass(
				$this->config['common_dir'],
				$this->config['base_dir'],
				$dir_name,
				$class_name
			);
		
		return new $class_name($this);

	}
	/**
	 * インスタンスを生成する
	 * パスが設定されていればそちらのディレクトリからクラスを取得する
	 *@param default_dir base_dirで見つからなかったらこちらに存在するファイルを取得
	 *@param base_dir このディレクトリに存在するファイルを優先して取得
	 *@param dir_name 指定フォルダ内にあるフォルダ名
	 *@param class_name クラス名
	 *@return 指定したクラスのファイル名
	 */
	private function getDirectoryClassFileName($default_dir, $base_dir,$dir_name,$class_name){
		$default_class_file = "$default_dir/$dir_name/$class_name.class.php";
		
		$class_file = "$base_dir/$dir_name/$class_name.class.php";
		if(!file_exists($class_file)){
			$class_file = $default_class_file;
		}
		return $class_file;
	}
}
