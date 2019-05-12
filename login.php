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

$content = include_template('login.php', [
	'category' => $category
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$loginInfo = $_POST;
	
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

	$check_email = "SELECT email FROM user WHERE email = ?";
	$result_check_email = mysqli_prepare($link, $check_email);
	$stmt_check = db_get_prepare_stmt($link, $check_email, [$loginInfo['email']]);
	mysqli_stmt_execute($stmt_check);
	$result_check_email = mysqli_stmt_get_result($stmt_check);
	$email_result = mysqli_fetch_assoc($result_check_email);

	if(!$email_result){
		$errors['email'] = 'Данный email не зарегистрирован';
	} else {
		$userData = "SELECT * FROM user WHERE email = ?";
		$result_userData = mysqli_prepare($link, $userData);
		$stmt_check = db_get_prepare_stmt($link, $userData, [$loginInfo['email']]);
		mysqli_stmt_execute($stmt_check);
		$result_userData = mysqli_stmt_get_result($stmt_check);
		$userData = mysqli_fetch_assoc($result_userData);
		$userName = $userData['name'];
		$password = $userData['pass'];
	}

	if (password_verify($loginInfo['password'], $password)) {
		session_start();
		$_SESSION['username'] = $userName;
		header("Location: index.php");
	} else {   
		$errors['password'] = 'Пароль введено не верно';
	}


	if (count($errors)) {
		$content = include_template('login.php', 
			[
				'category' => $category,
				'loginInfo'=> $loginInfo,
				'errors' => $errors
			]);
	}
}

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Вход на сайт',
		'is_auth' => $is_auth,
		'user_name' => $user_name,
		'category' => $category
	]);

print($layout_content);

?>