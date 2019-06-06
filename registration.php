<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if(!$link) {
	connect_db_error($link, 'Ошибка соединения с БД');
}

$category = get_all_category($link);

$content = include_template('reg.php', [
	'category' => $category
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$reginfo = $_POST;
	if (isset($reginfo['email'])) {
		$reginfo['email'] = esc($reginfo['email']);
	}
	if (isset($reginfo['name'])) {
		$reginfo['name'] = esc($reginfo['name']);
	}
	if (isset($reginfo['message'])) {
		$reginfo['message'] = esc($reginfo['message']);
	}		
	
	$path = $_FILES['img']['tmp_name'];
	$required_fields = ['email', 'password', 'name', 'message'];
	$errors = [];

	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}

	foreach ($reginfo as $key => $value) {
		if ($key === "email") {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$errors[$key] = 'Email должен быть корректным';
			}
		}
	} 
	
	if (isset($path) and !empty($path)) {
		$img_name = uniqid() . '.jpg';
		$img_path = __DIR__ . '/avatar/';
		$img_url = '/avatar/' . $img_name;
		$file_type = mime_content_type($path);
		if ($file_type !== 'image/jpeg') {	
			$errors['file'] = 'Загрузите картинку в JPEG';
		}
		else {
			move_uploaded_file($path, $img_path . $img_name);		
		}
	} else {
		$img_url = '';
	}

	$userInfo = get_info_user_by_email($link, $reginfo['email']);
	$email_result = $userInfo['email'];

	if(isset($email_result)){
		$errors['email'] = 'Данный email уже зарегистрирован';
	}

	if (count($errors)) {
		$content = include_template('reg.php', ['category' => $category, 'reginfo'=> $reginfo, 'errors' => $errors]);
	} else { 
		$passwordHash = password_hash($reginfo['password'], PASSWORD_DEFAULT);
		$reginfo['password'] = $passwordHash;

		$sql_add = "INSERT INTO user (dt_add, email, pass, name, avatar_path, contacts)
		VALUES (NOW(), ?, ?, ?, ?, ?)";

		$stmt = db_get_prepare_stmt($link, $sql_add, [$reginfo['email'], $reginfo['password'], $reginfo['name'], $img_url, $reginfo['message']]);
		$result_add = mysqli_stmt_execute($stmt);

		if ($result_add) {
			header("Location: login.php");
		} else {
			$error = mysqli_error($link);
			$content = include_template('error.php', ['text'=> 'Ошибка при проверке', 'error' => $error]);
		}	
	}	

}
$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Регистрация нового аккаунта', 'category' => $category]);
print($layout_content);
?>