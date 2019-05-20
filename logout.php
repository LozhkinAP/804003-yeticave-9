<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}

unset($_SESSION['username']);
header("Location: /index.php");

?>