#!/usr/bin/php
<?php

//service ディレクトリ内のドキュメントをディレクトリごとに作成する
if($argc < 2){
	echo "引数が少なすぎます。出力するディレクトリ名を指定してください。\n";
	echo "usase \n\t create_doc.php [output dir] [output type]\n\t example) $./create_doc.php ~/public_html HTML:Smarty:PHP\n";
	exit;
}

// 出力先
$output_dir = $argv[1];
//出力形式 phpdocコマンドの-oオプションと同じ
if(isset($argv[2])){
	$option = $argv[2];
}else{
	$option = "HTML:Smarty:PHP";
}
//サービスのディレクトリ
$service_dir = dirname(__FILE__)."/service/";
exec("svn up $service_dir");

$dir_list = get_dir_list($service_dir);

foreach($dir_list as $dir){
	$output = "$output_dir/$dir";
	exec("rm -rf $output");
	exec("mkdir $output");
	exec("phpdoc -t $output -d $service_dir/$dir -o $option");
}

//ディレクトリリストを取得する
function get_dir_list($dir){
	$dir_list = array();
	$res_dir = opendir($dir);
	while(false !== ($filename = readdir($res_dir))){
		if($filename != '.' && $filename != '..' && $filename != '.svn'){
			if(is_dir($dir.$filename)){
				$dir_list[] = $filename;
			}
		}
	}
	return $dir_list;
}

