<?php

require_once dirname(__FILE__)."/../../config.inc.php";

//サービスの追加を行うAPI

$controller = new Controller($conf);
$controller->dispatch('addService');

