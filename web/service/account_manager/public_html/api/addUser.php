<?php

require_once "../../config.inc.php";

//ユーザーの追加を行うAPI

$controller = new Controller($conf);
$controller->dispatch('addUser');

