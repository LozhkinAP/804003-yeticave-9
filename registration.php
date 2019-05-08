<?php
/*require_once 'data.php';*/
require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

if (!$link) {
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

$content = include_template('reg.php', [
	'category' => $category
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$reginfo = $_POST;
	$path = $_FILES['img']['tmp_name'];
	$required_fields = ['email', 'password', 'name', 'message'];
	$errors = [];

	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}

	foreach ($reginfo as $key => $value) {
		if ($key == "email") {
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
		if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {	
			$errors['file'] = 'Загрузите картинку в PNG или JPEG';
		}
		else {
			move_uploaded_file($path, $img_path . $img_name);		
		}
	}

	$check_email = "SELECT email FROM user WHERE email = ?";
	$result_check_email = mysqli_prepare($link, $check_email);
	$stmt_check = db_get_prepare_stmt($link, $check_email, [$reginfo['email']]);
	mysqli_stmt_execute($stmt_check);
	$result_check_email = mysqli_stmt_get_result($stmt_check);
	$email_result = mysqli_fetch_assoc($result_check_email); 	
	if($email_result){
		$errors['email'] = 'Данный email уже зарегистрирован';
	}

	if (count($errors)) {
		$content = include_template('reg.php', 
			[
				'category' => $category,
				'reginfo'=> $reginfo,
				'errors' => $errors
			]);
	}
	else { 
		$passwordHash = password_hash($reginfo['password'], PASSWORD_DEFAULT);
		$reginfo['password'] = $passwordHash;

		$sql_add = "INSERT INTO user (dt_add, email, pass, name, avatar_path, contacts)
		VALUES (NOW(), ?, ?, ?, ?, ?)";

		$stmt = db_get_prepare_stmt($link, $sql_add, 
			[
				$reginfo['email'],
				$reginfo['password'],
				$reginfo['name'],
				$img_url,
				$reginfo['message']
			]);

		$result_add = mysqli_stmt_execute($stmt);

		if ($result_add) {
			header("Location: login.php");
		}

		else {
			$error = mysqli_error($link);
			$content = include_template('error.php',
				[
					'text'	=> 'Ошибка выборки из БД',
					'error' => $error
				]);
		}	
	}	


}

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Регистрация нового аккаунта',
		'is_auth' => $is_auth,
		'user_name' => $user_name,
		'category' => $category
	]);

print($layout_content);

?>