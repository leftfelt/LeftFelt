<?php

require_once('../config.inc.php');

$controller = new Controller($conf);
$controller->dispatch('index');

