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

$content = include_template('lot_add.php', 
	[
		'category' => $category
	]);

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Добавление лота',
		'is_auth' => $is_auth,
		'user_name' => $user_name,
		'category' => $category
	]);

print($layout_content);

?>