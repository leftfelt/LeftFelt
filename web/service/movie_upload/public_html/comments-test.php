<?php

require_once('../libs/comments.class.php');

$id = @$_GET['id'];

if(!isset($id))exit;

$comments = new Comments();

$comments->create($id);

//print "<plaintext>".$comments->getXML()->saveXML();

$comments->add('test','flet',2,2,0,5);

//print "<plaintext>".$comments->getXML()->saveXML();
