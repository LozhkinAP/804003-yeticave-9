<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}

if (!isset($_GET['id'])) {
	error404($link, 'Укажите ID лота', 'Просмотр лота');
}

$category = getAllCategory($link);

/* Получаем лот по ID*/
$contentLot = getLotById($link, $_GET['id']);

if (!$contentLot) {
	error404($link, 'Не найден лот по указанному ID', 'Просмотр лота');
}

/* Узнаем минимальную ставку по лоту с id = $_GET['id'] из таблицы rate, она является не ставкой, а начальной ценой*/
$minRate = getMinRate($link, $_GET['id']);

/* Делаем выборку по всем ставкам данного лота, за исключением начальной цены (в нашем случае rate с минимальной ценой).*/
$rates = getRateById($link, $_GET['id'], $minRate['rate_price']);

$errors = [];
$required_fields = ['cost'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['cost'])) {
		$_POST['cost'] = htmlspecialchars($_POST['cost']);
	}
	if (!(is_numeric($_POST['cost']) && $_POST['cost'] > 0)) {
		$errors['cost'] = 'Введите целое положительное число';
	} else if ($_POST['cost'] < ($contentLot['rate_price'] + $contentLot['step_rate'])) {
		$errors['cost'] = 'Не корректная сумма, должно быть >= текущаяя цена + шаг ставки';
	}	

	if (empty($_POST['cost'])) {
		$errors['cost'] = 'Поле не заполнено';
	}

	if (empty($errors)) {
		$sql_addRate = "INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
		VALUES (NOW(), ?, ?, ?)";

		$stmt = db_get_prepare_stmt($link, $sql_addRate, [$_POST['cost'], $_SESSION['userid'], $contentLot['id']]);

		$result_add_rate = mysqli_stmt_execute($stmt);
		$id = $contentLot['id'];
		header("Location: /lot.php?id=$id");

	}	
}



$content = include_template('lot_detail.php', ['contentLot' => $contentLot, 'category' => $category, 'errors' => $errors, 'rates' => $rates
	]);

$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Просмотр лота', 'category' => $category]);

print($layout_content);

?>