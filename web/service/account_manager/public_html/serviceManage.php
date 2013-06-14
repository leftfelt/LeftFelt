<?php

require_once "../config.inc.php";

//サービスの追加、削除、一覧表示を行う　

$controller = new Controller($conf);
$controller->dispatch('serviceManage');

