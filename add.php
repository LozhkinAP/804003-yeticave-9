<?php
require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
	header("HTTP/1.1 403 Forbidden" ); 
	exit();
} else {
	$userId = $_SESSION['userid'];
}

if (!$link) {
	connect_db_error($link, 'Ошибка соединения с БД');
}

$category = get_all_category($link);

$content = include_template('lot_add.php', ['category' => $category]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$newLot = $_POST;
	if (isset($newLot['lot-name'])) {
		$newLot['lot-name'] = htmlspecialchars($newLot['lot-name']);
	}
	if (isset($newLot['message'])) {
		$newLot['message'] = htmlspecialchars($newLot['message']);
	}
	if (isset($newLot['lot-rate'])) {
		$newLot['lot-rate'] = htmlspecialchars($newLot['lot-rate']);
	}
	if (isset($newLot['lot-step'])) {
		$newLot['lot-step'] = htmlspecialchars($newLot['lot-step']);
	}	
	if (isset($newLot['lot-date'])) {
		$newLot['lot-date'] = htmlspecialchars($newLot['lot-date']);
	}		
	if (isset($newLot['category'])) {
		$newLot['category'] = htmlspecialchars($newLot['category']);
	}			
	 
	$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
	$errors = [];
	if (!(is_numeric($newLot['lot-rate']) && $newLot['lot-rate'] > 0)) {
		$errors['lot-rate'] = 'Введите число. (число должно быть > 0)';
	}
	if (!(is_numeric($newLot['lot-step']) && $newLot['lot-step'] > 0)) {
		$errors['lot-step'] = 'Введите число. (число должно быть > 0)';
	}
	if (!is_date_valid($newLot['lot-date'])) {
		$errors['lot-date'] = 'Введите дату в формате ГГГГ-ММ-ДД';
	} else {
		$newLot['lot-date'] = check_end_time_lot($newLot['lot-date']);
		if ($newLot['lot-date'] === 'error') {
			$errors['lot-date'] = 'Введите дату не ранее чем текущий момент + 24часа';
		}	
	}

	foreach ($required_fields as $field) {
		if (empty($_POST[$field])) {
			$errors[$field] = 'Поле не заполнено';
		}
	}

	$tmp_name = $_FILES['img']['tmp_name'];
	if (is_uploaded_file($tmp_name)) {
    	$path = $_FILES['img']['name'];
        $extentions = pathinfo($path, PATHINFO_EXTENSION);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
		if (($file_type === "image/png") || ($file_type === "image/jpg") || ($file_type === "image/jpeg")) {
			if ($extentions === "png") {
        		$img_name = uniqid() . '.png';
        	}
        	if ($extentions === "jpg")
        	{
        		$img_name = uniqid() . '.jpg';
        	}
        	if ($extentions === "jpeg")
        	{
        		$img_name = uniqid() . '.jpeg';
        	}
			$img_path = __DIR__ . '/uploads/';
			$img_url = '/uploads/' . $img_name;
			move_uploaded_file($tmp_name, $img_path . $img_name);			
		}
		else {
			$errors['file'] = 'Загрузите картинку в JPEG\JPG или PNG';		
		}
	}
	else {
		$errors['file']  = 'Вы не загрузили файл';
	}

	if(count($errors)) {
		$content = include_template('lot_add.php', ['category' => $category, 'newLot'=> $newLot, 'errors' => $errors]);
	}
	else {
		/* Получаем информацию о выбранной категории */
		$categoryInfo = get_category_by_name($link, $newLot['category']);

		/* Вставляем лот в таблицу lot и получаем лот по добавленному ID*/
		$sql = "INSERT INTO lot (dt_add, name, description, img_path, init_price, step_rate, category_id, end_lot_time, usercreate_id)
    			VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
		$addLotId = db_insert_data($link, $sql, [$newLot['lot-name'], $newLot['message'], $img_url, $newLot['lot-rate'], $newLot['lot-step'], $categoryInfo['id'], $newLot['lot-date'], $userId]);

		if (empty($addLotId)) {
			$error = mysqli_error($link);
			$content = include_template('error.php',['text'	=> 'Ошибка при добавлении лота', 'error' => $error]);
			$layout_content = include_template('layout.php', ['content' => $content]);
			print($layout_content);
			exit;
		}

		/*Добавляем в таблицу rate ставку на новый лот, равную начальный цене */
		$sql = "INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
				VALUES (NOW(), ?, ?, ?)";
		db_insert_data($link, $sql, [$newLot['lot-rate'], $userId, $addLotId]);	

		if (!empty($addLotId)) {
			header("Location: lot.php?id=" . $addLotId);
		}
	}
}
$layout_content = include_template('layout.php', ['content' => $content, 'title' => 'Добавление лота', 'category' => $category]);
print($layout_content);
?>