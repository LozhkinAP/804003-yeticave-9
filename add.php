<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
	header("HTTP/1.1 403 Forbidden" ); 
	exit();
}

if (!$link) {
	connectDbError($link, 'Ошибка соединения с БД');
}

$category = getAllCategory($link);

$content = include_template('lot_add.php', ['category' => $category]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$lot_new = $_POST;
	if (isset($lot_new['lot-name'])) {
		$lot_new['lot-name'] = htmlspecialchars($lot_new['lot-name']);
	}
	if (isset($lot_new['message'])) {
		$lot_new['message'] = htmlspecialchars($lot_new['message']);
	}
	if (isset($lot_new['lot-rate'])) {
		$lot_new['lot-rate'] = htmlspecialchars($lot_new['lot-rate']);
	}
	if (isset($lot_new['lot-step'])) {
		$lot_new['lot-step'] = htmlspecialchars($lot_new['lot-step']);
	}	
	if (isset($lot_new['lot-date'])) {
		$lot_new['lot-date'] = htmlspecialchars($lot_new['lot-date']);
	}		
	if (isset($lot_new['category'])) {
		$lot_new['category'] = htmlspecialchars($lot_new['category']);
	}			
	 
	$path = $_FILES['img']['tmp_name'];
	$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
	$errors = [];
	if (!(is_numeric($lot_new['lot-rate']) && $lot_new['lot-rate'] > 0)) {
		$errors['lot-rate'] = 'Введите число. (число должно быть > 0)';
	}
	if (!(is_numeric($lot_new['lot-step']) && $lot_new['lot-step'] > 0)) {
		$errors['lot-step'] = 'Введите число. (число должно быть > 0)';
	}
	if (!is_date_valid($lot_new['lot-date'])) {
		$errors['lot-date'] = 'Введите дату в формате ГГГГ-ММ-ДД';
	} else {
		$lot_new['lot-date'] = checkEndTimeLot($lot_new['lot-date']);
		if ($lot_new['lot-date'] === 'error') {
			$errors['lot-date'] = 'Введите дату не ранее чем текущий момент + 24часа';
		}	
	}

	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}
	if (isset($path) and !empty($path)) {
		$img_name = uniqid() . '.jpg';
		$img_path = __DIR__ . '/uploads/';
		$img_url = '/uploads/' . $img_name;
		$file_type = mime_content_type($path);
		if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {	
			$errors['file'] = 'Загрузите картинку в PNG или JPEG';
		}
		else {
			move_uploaded_file($path, $img_path . $img_name);		
		}
	}
	else {
		$errors['file']  = 'Вы не загрузили файл';
	}

	if(count($errors)) {
		$content = include_template('lot_add.php', ['category' => $category, 'lot_new'=> $lot_new, 'errors' => $errors]);
	}
	else {
		/* Получаем выбранную категорию */
		$category_id = getCategoryByName($link, $lot_new['category']);

		/* Вставляем лот в таблицу lot и получаем лот по добавленному ID*/
		$userID = $_SESSION['userid'];
		$sqlAdd = "INSERT INTO lot (dt_add, name, description, img_path, init_price, step_rate, category_id, end_lot_time, usercreate_id)
    VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
		$lot_id = db_insert_data($link, $sqlAdd, [$lot_new['lot-name'], $lot_new['message'], $img_url, $lot_new['lot-rate'], $lot_new['lot-step'], $category_id['id'], $lot_new['lot-date'], $userID]);

		if (empty($lot_id)) {
			$error = mysqli_error($link);
			$content = include_template('error.php',
				['text'	=> 'Ошибка при добавлении лота', 'error' => $error]);
			$layout_content = include_template('layout.php', ['content' => $content]);
			print($layout_content);
			exit;
		}

		/*Добавляем в таблицу rate ставку на новый лот, равную начальный цене (вдальнейшем при создании ставка на лот цена будет начальная цена + ставка)*/	
		$sql_addRate = "INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
		VALUES (NOW(), ?, ?, ?)";

		$stmt = db_get_prepare_stmt($link, $sql_addRate, 
			[$lot_new['lot-rate'], $userID, $lot_id]);

		$result_add_rate = mysqli_stmt_execute($stmt);		
		
		if (!empty($lot_id)) {
			header("Location: lot.php?id=" . $lot_id);
		}

	}
}
$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Добавление лота', 'category' => $category]);
print($layout_content);
?>