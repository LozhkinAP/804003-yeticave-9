<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	connect_db_error($link, 'Ошибка соединения с БД');
}

if (!isset($_GET['id'])) {
	error404($link, 'Укажите ID лота', 'Просмотр лота');
}

if (isset($_SESSION['userid'])) {
	$userId = $_SESSION['userid'];
}


if (isset($_GET['id'])) {
	$id = htmlspecialchars($_GET['id']);
}

$category = get_all_category($link);
$lotById = get_lot_by_id($link, $id);

if (!isset($lotById)) {
	error404($link, 'Не найден лот по указанному ID', 'Просмотр лота');
}

$rates = get_rate_by_lot_id($link, $id);
$lastRateUser = last_rate_user($link, $id);

$errors = [];
$required_fields =['rate'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['rate'])) {
		$rate = htmlspecialchars($_POST['rate']);
	}
	if (!(is_numeric($rate) && $rate > 0)) {
		$errors['rate'] = 'Введите целое положительное число';
	} else if ($_POST['rate'] < ($lotById['rate_price'] + $lotById['step_rate'])) {
		$errors['rate'] = 'Не корректная сумма, должно быть >= текущаяя цена + шаг ставки';
	}	
	if (empty($rate)) {
		$errors['rate'] = 'Поле не заполнено';
	}
	if (empty($errors)) {
		add_rate_by_lot($link, $rate, $userId, $id);
		header("Location: /lot.php?id=$id");
	}	
}

$content = include_template('lot_detail.php', ['lotById' => $lotById, 'category' => $category, 'errors' => $errors, 'rates' => $rates, 'lastRateUser' => $lastRateUser]);

$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Просмотр лота', 'category' => $category]);

print($layout_content);

?>