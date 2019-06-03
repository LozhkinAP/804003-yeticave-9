<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	connect_db_error($link, 'Ошибка соединения с БД');
}

$category = get_all_category($link);
/* Узнаем количество ставок по текущему $userID, исключая из этого списка начальные цены лотов (минимальные ставки в табл. rate, которые записываются в таблицу rate при добавлении нового лота)*/
$best = get_rate_by_user($link, esc($_SESSION['userid']));

$content = include_template('best.php', ['category' => $category, 'best' => $best]);

$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Мои ставки', 'category' => $category]);

print($layout_content);

?>