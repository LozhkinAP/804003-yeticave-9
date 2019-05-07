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

if(!isset($_GET['id'])){
	http_response_code(404);
	$error = http_response_code();
	$content = include_template('error.php',
		[
			'text'	=> 'Не указан номер лота! Ошибка',
			'error' => $error
		]);

	$layout_content = include_template('layout.php', 
		[	
			'content' => $content
		]);
	print($layout_content);
	exit;
}

$sql_lotdetail = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, c.name category,  r.rate_price rate_price
FROM lot AS l 
INNER JOIN categories as c ON l.category_id = c.id
INNER JOIN rate AS r ON r.lot_id = l.id 
WHERE l.id = ?";

$result_lotdetail = mysqli_prepare($link, $sql_lotdetail);
$stmt = db_get_prepare_stmt($link, $sql_lotdetail, [$_GET['id']]);
mysqli_stmt_execute($stmt);
$result_lotdetail = mysqli_stmt_get_result($stmt);
$content_lot = mysqli_fetch_assoc($result_lotdetail); 

$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($link, $sql_category);
$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

if(!$content_lot){
	http_response_code(404);
	$error = http_response_code();
	$content = include_template('error.php',
		[
			'text'	=> 'Не найден лот по указанному номеру',
			'error' => $error
		]);

	$layout_content = include_template('layout.php', 
		[	
			'content' => $content
		]);
	print($layout_content);
	exit;	
}

$content = include_template('lot_detail.php', [
		'content_lot' => $content_lot,
		'category' => $category
	]);

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Просмотр лота',
		'is_auth' => $is_auth,
		'user_name' => $user_name,
		'category' => $category
	]);

print($layout_content);

?>