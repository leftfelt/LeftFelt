#!/usr/bin/php
<?php
//config.inc.phpがなければ作成、ある場合はマージを行う
$base_dir = realpath(dirname($argv[0])).'/';

$config_file = $base_dir."config.inc.php";
$config_template_file = $base_dir."config.template.php";

$temp_conf = array();
$require_list = array();

if(is_file($config_file)) require_once $config_file;
if(is_file($config_template_file))require_once $config_template_file;


//ファイル書き出し用のconfig
$config = array();
$conf_only = array();

//ファイルが存在していればマージ
if(is_file($config_file)){
	foreach($temp_conf as $key => $value){
		if(array_key_exists($key,$conf)){
			//$config_fileにkeyがある場合は、値を更新しない
			$config[$key] = $conf[$key];
		}else{
			//keyがなければtemplateの値を代入
			$config[$key] = $value;
		}
	}
	//config.inc.phpに直書きされているのはそのまま
	foreach($conf as $key => $value){
		if(!array_key_exists($key,$temp_conf)){
			//$config_fileにkeyがある場合は、値を更新しない
			$conf_only[$key] = $conf[$key];
		}
	}

}else{
	//ファイル無いから作るよ
	$config = $temp_conf;
}

//書き込み処理
$fp = fopen($config_file,'w');
fprintf($fp,"<?php\n");
writeRequire($fp,$require_list);
fprintf($fp,"\n");
fprintf($fp,"//====================config.template.phpとの共通項目====================\n");
writeConfig($fp,$config);
fprintf($fp,"//====================config.inc.phpのみの項目====================\n");
writeConfig($fp,$conf_only);
fclose($fp);

echo $config_file."\n";

//読み込みファイルを書き出す　
function writeRequire($fp, $require_list){
	foreach($require_list as $key => $value){
		fprintf($fp,"require_once %s;\n", $value);
	}
}

//ファイルにconfigを書き出す
function writeConfig($fp, $config){
	foreach($config as $key => $value){
		$key = "['$key']";
		fprintf($fp, "\$conf%s = %s;\n", $key, var_export($value,true));
	}
}

