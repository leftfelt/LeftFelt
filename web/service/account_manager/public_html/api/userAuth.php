<?php
require_once dirname(__FILE__)."/../../config.inc.php";

//ユーザ認証を行うAPI

$controller = new Controller($conf);
$controller->dispatch('userAuth');

