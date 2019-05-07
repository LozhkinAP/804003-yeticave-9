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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$lot_new = $_POST;

	$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
	$errors = [];
	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}
	if (isset($_FILES['img']['tmp_name'])) {
		$path = $_FILES['img']['tmp_name'];
		$img_name = uniqid() . '.jpg';
		$img_path = __DIR__ . '/uploads/';
		$img_url = '/uploads/' . $img_name;
		$file_type = mime_content_type($path);
		if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {	
			$errors['file'] = 'Загрузите картинку в PNG или JPEG';
		}
		else {
			move_uploaded_file($_FILES['img']['tmp_name'], $img_path . $img_name);		
		}
	}
	else {
		$errors['file']  = 'Вы не загрузили файл';
	}

	if (!(is_numeric($_POST['lot-rate']) && $_POST['lot-rate'] > 0)){
		$errors['lot-rate'] = 'Введите число. (число должно быть > 0)';
	}

	if (!(is_numeric($_POST['lot-step']) && $_POST['lot-step'] > 0)){
		$errors['lot-step'] = 'Введите число. (число должно быть > 0)';
	}

	if (!is_date_valid($_POST['lot-date'])){
		$errors['lot-date'] = 'Введите дату в формате ГГГГ-ММ-ДД';
	}

	if(count($errors)) {
		$content = include_template('lot_add.php', 
			[
				'category' => $category,
				'lot_new'=> $lot_new,
				'errors' => $errors
			]);
	}
	else {
		$sql_category_id = "SELECT id FROM categories WHERE name = ?";
		$result_category_id = mysqli_prepare($link, $sql_category_id);
		$stmt_id = db_get_prepare_stmt($link, $sql_category_id, [$lot_new['category']]);
		mysqli_stmt_execute($stmt_id);
		$result_category_id = mysqli_stmt_get_result($stmt_id);
		$category_id = mysqli_fetch_assoc($result_category_id); 

		$sql_add = "INSERT INTO lot (dt_add, name, description, img_path, init_price, step_rate, category_id, end_lot_time, usercreate_id, uservictory_id)
		VALUES (NOW(), ?, ?, ?, ?, ?, ?, ? , 1, 1)";

		$stmt = db_get_prepare_stmt($link, $sql_add, 
			[
				$lot_new['lot-name'],
				$lot_new['message'],
				$img_url,
				$lot_new['lot-rate'],
				$lot_new['lot-step'],
				$category_id['id'],
				$lot_new['lot-date']
			]);

		$result_add = mysqli_stmt_execute($stmt);

		if ($result_add) {
			$lot_id = mysqli_insert_id($link);
			header("Location: lot.php?id=" . $lot_id);
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
		'title' => 'Добавление лота',
		'is_auth' => $is_auth,
		'user_name' => $user_name,
		'category' => $category
	]);


print($layout_content);
?>