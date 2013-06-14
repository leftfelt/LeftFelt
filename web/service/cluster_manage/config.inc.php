<?php
require_once dirname(__FILE__).'/../config.inc.php';
require_once dirname(__FILE__).'/lib/core/Controller.class.php';

//====================config.template.phpとの共通項目====================
$conf['service_name'] = 'cluster_manage';
$conf['service_url'] = 'http://dev.leftfelt.net/~flet/cluster_manage';
$conf['base_dir'] = '/home/flet/workspace/trunk/web/service/cluster_manage';
$conf['library_dir'] = '/home/flet/workspace/trunk/web/service/cluster_manage/lib';
//====================config.inc.phpのみの項目====================
