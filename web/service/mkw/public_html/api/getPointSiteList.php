<?php

require_once dirname(__FILE__)."/../../config.inc.php";

$controller = new Controller($conf);
$controller->dispatch('getPointSiteList');
