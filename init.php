<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'functions.php';
require_once 'helpers.php';
require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']);
mysqli_set_charset($link, "utf8");

session_start();

?>