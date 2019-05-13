<?php
/*require_once 'data.php';*/
require_once 'helpers.php';
require_once 'functions.php';
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

$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($link, $sql_category);
$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

$search = trim($_GET['search']) ?? '';
if ($search) {
	$sql_lots = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, l.description description, c.name category, l.end_lot_time end_time
	FROM lot as l 
	INNER JOIN categories as c ON l.category_id = c.id 
	WHERE MATCH (l.name, l.description) AGAINST(?)
	ORDER BY l.id DESC";

	$result_prepare = mysqli_prepare($link, $sql_lots);
	$stmt = db_get_prepare_stmt($link, $sql_lots, [$search]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

	if (empty($lots)) {
		$content = include_template('error.php',
			[
				'text'	=> 'Ничего не найдено по вашему запросу',
			]);

		$layout_content = include_template('layout.php', 
			[	
				'content' => $content
			]);
		print($layout_content);
		exit;
	}
} 

$content = include_template('search.php', [
	'category' => $category,
	'lots' => $lots
]);

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Мои ставки',
		'category' => $category
	]);

print($layout_content);

?>