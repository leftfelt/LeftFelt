<?php

require_once "../../config.inc.php";

//サービスリストの取得を行うAPI

$controller = new Controller($conf);
$controller->dispatch('getServiceList');

