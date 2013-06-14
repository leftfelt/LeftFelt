<?php

require_once "../config.inc.php";

//ポイントサイトの追加、削除、一覧表示を行う　

$controller = new Controller($conf);
$controller->dispatch('PointSiteManage');

