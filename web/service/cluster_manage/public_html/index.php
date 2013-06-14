<?php

require_once dirname(__FILE__)."/../config.inc.php";

//トップページ

$controller = new Controller($conf);
$controller->dispatch("index");
