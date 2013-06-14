<?php
require_once('../libs/functions.php');

$db = db_connect();

$sql = "select count * from tags;";

$stmt = $db->prepare($sql);
$stmt->execute();

print_r($stmt->fetchAll());

$stmt->execute();

print_r($stmt->fetchAll());
