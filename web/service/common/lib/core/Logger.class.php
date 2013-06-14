<?php
//実行ファイルと同じ階層のLogディレクトリにlogファイルを出力する
class Logger{
	private $prefix;

	/**
	 * @param $prefix log/以下に作成されるディレクトリ名,logへの出力時にメッセージと一緒に出力する
	 * @param $filename infoを出力するファイル名(拡張子以外 自動的に.txtがつく)
	 */
	public function __construct($prefix="",$filename="error_log"){

		$this->log_dir = "log/$prefix";
		
		//接頭辞
		if($prefix !== ""){
			$this->prefix = "[$prefix]";
			$prefix = "$prefix/";
		}else{
			$this->prefix = $prefix;
		}

		$postfix = date('ymd');
		$this->log_file = "log/$prefix$filename"."_$postfix.txt";
	}

	public function Info($message){
		$this->printMessage("[info]",$message);
	}

	public function Error($message){
		$this->printMessage("[error]",$message);
	}

	public function Warning($message){
		$this->printMessage("[warning]",$message);
	}

	private function isPrefixEmpty(){
		return ( $this->prefix !== "");
	}
	
	private function createLogDir(){
		if(!is_dir($this->log_dir) && $this->isPrefixEmpty()){
			//log/$prefixディレクトリが存在しない
			$old_mask = umask(0);
			mkdir($this->log_dir,0777,true);
			umask($old_mask);
		}
	}

	private function printMessage($status,$message){
		$date = date("Y/m/d H:i:s");
		//ディレクトリなかったら作る
		$this->createLogDir();

		$fp = fopen($this->log_file,'a');
		if(!$fp) return; //失敗しても気にしない！（ここで時間とられてもしょうがない）
		if(!flock($fp,LOCK_EX))return;//ロック

		chmod($this->log_file,0666);
		//失敗しても気にしない！（ここで時間とられてもしょうがない）
		fwrite($fp,"$date $status $this->prefix $message\n");
		
		if(!flock($fp,LOCK_UN))return;//ロック解除
	}
}
