#!/usr/bin/php
<?php

//実行したディレクトリにしてしたサービス名のディレクトリを作成する
require_once dirname(__FILE__).'/../config.inc.php';

if($argc < 2){
	echo "引数が足りません。環境名を第一引数に指定してください。\n";
	echo "usage ./create_service.php sample\n";
	echo "例）sampleという環境ディレクトリを作成する\n";
	exit;
}

// 環境名
$service_name = $argv[1];

//環境に作りたいディレクトリリスト
$directory_list = array();
$directory_list[] = "public_html";
$directory_list[] = "templates";
$directory_list[] = "action";
$directory_list[] = "view";
$directory_list[] = "batch";
$directory_list[] = "lib/renderer";
$directory_list[] = "lib/model";
$directory_list[] = "lib/logic";
$directory_list[] = "lib/filter";
$directory_list[] = "lib/component";
$directory_list[] = "public_html/log";
$directory_list[] = "public_html/js";
$directory_list[] = "public_html/css";
$directory_list[] = "public_html/api";
$directory_list[] = "temp/templates_c";
$directory_list[] = "test/renderer";
$directory_list[] = "test/model";
$directory_list[] = "test/logic";
$directory_list[] = "test/filter";

//環境のディレクトリを作成する
createServiceDirectory($service_name, $directory_list);

//commonを追加したいディレクトリリスト
$directory_list = array();
$directory_list[] = "public_html";
$directory_list[] = "templates";

//ディレクトリにcommonディレクトリを追加する 
addCommonDirectory($conf, $service_name, $directory_list);

//パーミッションを設定する
exec("chmod 777 $service_name/temp/templates_c");
exec("chmod 777 $service_name/public_html/log");

//coreへのシンボリックリンクを作成
exec("ln -s ".$conf['common_dir']."/lib/core $service_name/lib/core");
//設定更新ファイル
exec("ln -s ".$conf['common_dir']."/public_html/update_config.php $service_name/update_config");

//自動テスト用ファイルをコピー
$watchr_file = $conf['common_dir']."/phpunit.watchr";
$autotest_file = $conf['common_dir']."/autotest";
exec("cp $watchr_file $service_name");
exec("cp $autotest_file $service_name");

//設定ファイルのテンプレートをコピー
$template_config = dirname(__FILE__)."/config.template.php";
$service_config = "$service_name/config.template.php";
exec("cp $template_config $service_config");

//設定内の置換対象を置換対象
//{@service_name}
replaceConfigValue($template_config, $service_config, "service_name", $service_name);

//サンプルページ用のファイルをコピー
$sample_file_list = array();
$sample_file_list[] = 'public_html/index.php';
$sample_file_list[] = 'action/indexAction.class.php';
$sample_file_list[] = 'view/indexView.class.php';
$sample_file_list[] = 'templates/index.html';

foreach($sample_file_list as $file){
	exec("cp ".$conf['common_dir']."/$file $service_name/$file");
}
//=============ファイル追加、変更はここまで=============
//update_configを実行
exec("./$service_name/update_config");

//バージョン管理下に置く
exec("svn add $service_name");
exec("svn propset -R svn:ignore '*.log' $service_name");
exec("svn propset svn:ignore '*.inc.php' $service_name");
exec("svn propset svn:ignore '*.html.php' $service_name/temp/templates_c");

//============================================================================
function replaceConfigValue($template_config, $service_config, $key, $value){
	$key = "{@$key}";
	exec("(sed -e \"s/$key/$value/g\" $template_config) > $service_config");
}

//環境ディレクトリ以下に新規ディレクトリを作成する（中間ディレクトリも作るよ）
function createServiceDirectory($service_name,$directory_list){
	foreach($directory_list as $directory){
		exec("mkdir -p $service_name/$directory");
	}
}

//ディレクトリにcommonディレクトリを追加する 
//といいつつシンボリックリンクを追加するよ
function addCommonDirectory($conf, $service_name, $directory_list){
	foreach($directory_list as $directory){
		exec("ln -s ".$conf['common_dir']."/$directory $service_name/$directory/common");
	}
}

