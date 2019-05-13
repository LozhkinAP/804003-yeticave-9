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

if (!isset($_GET['id'])) {
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

/* Получаем данные лота с id = $_GET['id']*/

$sql_lotdetail = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, l.end_lot_time end_time, c.name category,  MAX(r.rate_price) rate_price
FROM lot AS l 
INNER JOIN categories as c ON l.category_id = c.id
INNER JOIN rate AS r ON r.lot_id = l.id 
WHERE l.id = ?
GROUP BY l.id";

$result_lotdetail = mysqli_prepare($link, $sql_lotdetail);
$stmt = db_get_prepare_stmt($link, $sql_lotdetail, [$_GET['id']]);
mysqli_stmt_execute($stmt);
$result_lotdetail = mysqli_stmt_get_result($stmt);
$content_lot = mysqli_fetch_assoc($result_lotdetail); 

/* Узнаем минимальную ставку по лоту с id = $_GET['id'] из таблицы rate, она является не ставкой, а начальной ценой*/
$sql_minRate = "SELECT lot_id, user_id, MIN(rate_price) AS rate_price
FROM rate
WHERE lot_id = ?
GROUP BY lot_id, user_id";
$minRate_prepare = mysqli_prepare($link, $sql_minRate);
$stmt2 = db_get_prepare_stmt($link, $sql_minRate, [$_GET['id']]);
mysqli_stmt_execute($stmt2);
$minRate_result = mysqli_stmt_get_result($stmt2);
$minRate= mysqli_fetch_assoc($minRate_result);

/* Делаем выборку по всем ставкам данного лота, за исключением начальной цены (в нашем случае rate с минимальной ценой).*/

$sql_rates = "SELECT r.dt_rate, r.user_id, r.rate_price, r.lot_id, u.name
FROM rate AS r
INNER JOIN user AS u ON r.user_id = u.id
WHERE r.lot_id = ? AND r.rate_price != ?
ORDER BY r.dt_rate DESC";
$rates_prepare = mysqli_prepare($link, $sql_rates);
$stmt3 = db_get_prepare_stmt($link, $sql_rates, [$_GET['id'],$minRate['rate_price']]);
mysqli_stmt_execute($stmt3);
$rates_result = mysqli_stmt_get_result($stmt3);
$rates = mysqli_fetch_all($rates_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$required_fields = ['cost'];
	$errors = [];
	
	if (!(is_numeric($_POST['cost']) && $_POST['cost'] > 0)) {
		$errors['cost'] = 'Введите целое положительное число';
	} else if ($_POST['cost'] < ($content_lot['rate_price'] + $content_lot['step_rate'])){
		$errors['cost'] = 'Не корректная сумма, должно быть >= текущаяя цена + шаг ставки';
	}	

	if (empty($_POST['cost'])) {
		$errors['cost'] = 'Поле не заполнено';
	}

	if (empty($errors)) {
		$sql_addRate = "INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
		VALUES (NOW(), ?, ?, ?)";

		$stmt = db_get_prepare_stmt($link, $sql_addRate, 
			[
				$_POST['cost'],
				$_SESSION['userid'],
				$content_lot['id']
			]);

		$result_add_rate = mysqli_stmt_execute($stmt);
		$id = $content_lot['id'];
		header("Location: /lot.php?id=$id");

	}	
}

$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($link, $sql_category);
$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

if (!$content_lot) {
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
		'category' => $category,
		'errors' => $errors,
		'rates' => $rates
	]);

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Просмотр лота',
		'category' => $category
	]);


print($layout_content);

?>