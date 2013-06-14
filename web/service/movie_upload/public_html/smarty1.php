<?php
$scalar = "Hello Smarty!";
$array = array("m" => "男", "f" => "女");
var_dump($array);

require_once("../libs/MySmarty.class.php");
$smarty = new MySmarty();
$smarty->assign("scalar", $scalar);
$smarty->assign("sex" , $array);
$smarty->display("smarty1.html");

