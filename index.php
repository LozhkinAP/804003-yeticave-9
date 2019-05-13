<?php
/*require_once 'data.php';*/
require_once 'functions.php';
require_once 'helpers.php';
require_once 'init.php';

if(!$link) {
	$error = mysqli_connect_error($link);
	$content = include_template('error.php',
		[
			'text'	=> 'Ошибка соединения с БД',
			'error' => $error
		]);

	$layout_content = include_template('layout.php', 
		[	
			'content' => $content
		]);
	print($layout_content);
	exit;
}

$sql_lots = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, c.name category, l.end_lot_time end_time
			FROM lot as l 
			INNER JOIN categories as c ON l.category_id = c.id 
			ORDER BY l.id DESC";
$sql_category = "SELECT * FROM categories";
$result_lots = mysqli_query($link, $sql_lots);
$result_category = mysqli_query($link, $sql_category);

if(!$result_category || !$result_lots) {
	$error = mysqli_error($link);
	$content = include_template('error.php',
		[
			'text'	=> 'Ошибка выборки из БД',
			'error' => $error
		]);
	$layout_content = include_template('layout.php', 
		[
			'content' => $content
		]);
	print($layout_content);
	exit;
}

$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
$content = include_template('index.php', 
	[
		'lots' => $lots,
		'category' => $category
	]);	
$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Главная страница',
		'category' => $category
	]);
print($layout_content);
?>