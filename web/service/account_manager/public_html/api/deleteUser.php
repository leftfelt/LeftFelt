<?php

require_once "../../config.inc.php";

//ユーザーの削除を行うAPI

$controller = new Controller($conf);
$controller->dispatch('deleteUser');

