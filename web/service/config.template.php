<?php
//共通で使用するライブラリ等はここでインクルードする
$require_list = array(
	"dirname(__FILE__).'/common/lib/core/Controller.class.php'",
);

//データベース接続先
$temp_conf['db-main']['host'] = 'localhost';
$temp_conf['db-main']['dbname'] = 'leftfelt';
$temp_conf['db-main']['user'] = 'leftfelt';
$temp_conf['db-main']['pass'] = 'uglxc229';

//redisの接続先
$temp_conf['redis-main']['host'] = '127.0.0.1';
$temp_conf['redis-main']['port'] = '6379';
//memcacheの接続先
$temp_conf['memcache-main']['host'] = '127.0.0.1';
$temp_conf['memcache-main']['port'] = '11211';

//パスワード暗号化用config
$temp_conf['password_hash']['stretching'] = 50000;
$temp_conf['password_hash']['prefix'] = '!left>';
$temp_conf['password_hash']['postfix'] = '<felt#';

//キャッシュ時間(秒)
$temp_conf['cache_call_short'] = 5; 
$temp_conf['cache_call_middle'] = 180;
$temp_conf['cache_call_long'] = 300;

//環境フォルダのルートパス(PHPからみた)
$temp_conf['base_url']='http://www.leftfelt.net/~leftfelt';
$temp_conf['image_crawler_url'] = $temp_conf['base_url'].'/image_crawler';
$temp_conf['account_manager_url'] = $temp_conf['base_url'].'/account_manager';
$temp_conf['userauth_url'] = $temp_conf['base_url'].'/account_manager/api/userAuth.php';

$temp_conf['login_url'] = $temp_conf['base_url'].'/common/login.php';
$temp_conf['login_expire'] = 60 * 60 * 24 * 30 * 6; //半年

//共通で扱うファイル
$temp_conf['common_dir']=dirname(__FILE__).'/common';
$temp_conf['common_library_dir'] = $temp_conf['common_dir'].'/lib';

$temp_conf['base_dir'] = dirname(__FILE__);
