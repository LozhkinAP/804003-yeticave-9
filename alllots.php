<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	ConnectDbError($link, 'Ошибка соединения с БД');
}

if (!isset($_GET['category'])) {
	error404('Укажите категорию лотов');
}
/* список категорий */
$category = getAllCategory($link);
/* категория по ID категории */
$categoryById = getCategoryById($link, $_GET['category']);

$cur_page = $_GET['page'] ?? 1;
$page_items = 3;

$sql = "SELECT COUNT(*) as cnt FROM lot WHERE category_id = ?";
$stmt = db_get_prepare_stmt($link, $sql, [$_GET['category']]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$result = mysqli_fetch_assoc($res);
$items_count = $result['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

/* список лотов по по указанной категории*/
$lots = getLotsByLimit($link, $_GET['category'], $page_items, $offset);

$content = include_template('alllots.php', [
	'lots' => $lots, 
	'category' => $category, 
	'categoryById' => $categoryById, 
	'pages_count' => $pages_count,
	'pages' => $pages,
	'cur_page' => $cur_page

]);

$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Просмотр лота', 'category' => $category]);

print($layout_content);

?>