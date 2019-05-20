<?php
require_once 'vendor/autoload.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'helpers.php';
require_once 'getWinner.php';

$category = getAllCategory($link);
$lots = getAllLots($link);

if(!empty($lots)) {
	$content = include_template('index.php', ['lots' => $lots, 'category' => $category]);	
	$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Главная страница', 'category' => $category]);
} else {
	$layout_content = selectDbError('Ошибка выборки из БД, либо ни одного лота еще не существует');
}

print($layout_content);
?>