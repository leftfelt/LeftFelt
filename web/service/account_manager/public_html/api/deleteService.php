<?php

require_once "../../config.inc.php";

//サービスの削除を行うAPI

$controller = new Controller($conf);
$controller->dispatch('deleteService');

