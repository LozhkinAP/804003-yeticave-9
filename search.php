<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}
$category = getAllCategory($link);
$search = trim($_GET['search']) ?? '';
$cur_page = $_GET['page'] ?? 1;
$page_items = 3;
$sql = "SELECT COUNT(*) as cnt FROM lot WHERE MATCH (name, description) AGAINST(?)";
$stmt = db_get_prepare_stmt($link, $sql, [$_GET['search']]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$result = mysqli_fetch_assoc($res);
$items_count = $result['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);
if ($search) {
	$lots = getSearch($link, $search, $page_items, $offset);
	if (empty($lots)) {
		error404($link, 'Не найден лот по вашему запросу', 'Результаты поиска');
	}
} 
$content = include_template('search.php',  [
	'lots' => $lots, 
	'category' => $category,
	'search' =>  $search,
	'pages_count' => $pages_count,
	'pages' => $pages,
	'cur_page' => $cur_page

]);
$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Результаты поиска', 'category' => $category]);
print($layout_content);
?>