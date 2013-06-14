<?php
require_once("../libs/MySmarty.class.php");

session_start();

$name = @$_SESSION['login'];

$smarty = new MySmarty();
$smarty->assign("name",$name);
$smarty->display("index.html");
