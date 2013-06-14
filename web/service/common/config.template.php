<?php

require_once dirname(__FILE__)."/../config.inc.php";

//全体の共通config
$require_list = array(
	"dirname(__FILE__).'/../config.inc.php'",
	"dirname(__FILE__).'/lib/core/Controller.class.php'",
);

//この環境用のconfig等はいかに追加する
$temp_conf['service_name'] = 'common';
$temp_conf['service_url'] = $conf['base_url'].'/'.$temp_conf['service_name'];
$temp_conf['base_dir'] = $conf['base_dir'].'/'.$temp_conf['service_name'];
$temp_conf['library_dir'] = $temp_conf['base_dir'].'/lib';
