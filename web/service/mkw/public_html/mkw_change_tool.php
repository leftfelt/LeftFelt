<?php

require_once('../config.inc.php');

class MkwChangeTool extends Controller{
	public function execute(){
		//フォルダならファイル一覧をファイルなら内容を表示する
		$root_dir = dirname(__FILE__).'/../source';

		$dir = $this->request->getParametor('dir','/');
		$contents_data = $this->request->getParametor('contents_data');

		if($contents_data){
			$fp = fopen($root_dir.$dir,'w');
			$response = array();
			$response['status'] = false;
			if($fp){
				if(flock($fp,LOCK_EX)){
					if(fwrite($fp, $contents_data) === false){
						$response['message'] = 'ファイルの書き込みに失敗しました';
					}else{
						$response['status'] = true;
						$response['data'] = $contents_data;
					}
				}else{
					$response['message'] = 'ファイルがロックされています。他の人が操作しています。';
				}
			}else{
				$response['message'] = 'ファイルを開くことができませんでした。';
			}
			echo json_encode($response);
			exit;
		}

		//相対パスを使わせない
		if(preg_match("(\.\.)",$dir)) {
			$dir="";
			echo '相対パスを使うことはできません';
		}

		$this->request->setAttribute('dir',$dir);
		if(!is_dir($root_dir.$dir)){
			//ファイル編集画面を開く
			$file_data = file_get_contents($root_dir.$dir);
			$this->request->setAttribute('file_data',$file_data);
		}else{
			//ファイルリストを表示する
			$file_list = $this->file_list($root_dir.$dir);
			$this->request->setAttribute('file_list',$file_list);
		}
	
		//HTMLファイルに変数を渡す
		$renderer = $this->getRenderer('Manage');
		$renderer->setAttributes($this->request->getAttributes());
		$renderer->setTemplate('mkw_change_tool.html');
		$renderer->setJsList(array(
			'js/run_mkw_change_tool.js',
		));
		return $renderer;
	}

	//ファイルリストを取得する
	private function file_list($dir){
		$file_list = array();
		$res_dir = opendir($dir);
		while(false !== ($filename = readdir($res_dir))){
			if($filename != '.' && $filename != '..' && $filename != '.svn'){
				if(is_dir($dir.$filename)){
					$file_list[] = $filename.'/';
				}else{
					$file_list[] = $filename;
				}
			}
		}
		return $file_list;
	}
}

$controller = new MkwChangeTool($conf);
$controller->dispatch();
