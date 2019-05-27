<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}

$category = getAllCategory($link);

$content = include_template('login.php', [
	'category' => $category
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$loginInfo = $_POST;
	$loginInfo['email'] = htmlspecialchars($loginInfo['email']);
	
	$required_fields = ['email', 'password'];
	$errors = [];

	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}

	foreach ($loginInfo as $key => $value) {
		if ($key == "email") {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$errors[$key] = 'Email должен быть корректным';
			}
		}
	} 

	$userInfo = getInfoUserByEmail($link, $loginInfo['email']);

	if (!$userInfo) {
		$errors['email'] = 'Данный email не зарегистрирован';
	} else {
		$userName = $userInfo['name'];
		$password = $userInfo['pass'];
		$userId = $userInfo['id'];
	}

	if (password_verify($loginInfo['password'], $password)) {
		$_SESSION['username'] = $userName;
		$_SESSION['userid'] = $userId;
		header("Location: index.php");
	} else {   
		$errors['password'] = 'Пароль введено не верно';
	}


	if (count($errors)) {
		$content = include_template('login.php', ['category' => $category, 'loginInfo'=> $loginInfo, 'errors' => $errors]);
	}
}

$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Вход на сайт', 'category' => $category]);
print($layout_content);
?>