<?php
/*require_once 'data.php';*/
require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

if(!$link){
	$error = mysqli_connect_error($link);
	$lot_content = include_template('error_connect.php', ['error' => $error]);
}
else{
	if(!isset($_GET['id'])){
		$error = 'Не указан номер лота!';
		$lot_content = include_template('error_lot.php', ['error' => $error]);
	}
	else{

		$id = $_GET['id'];
		$sql_lotdetail = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, c.name category,  r.rate_price rate_price
			FROM lot AS l 
			INNER JOIN categories as c ON l.category_id = c.id
			INNER JOIN rate AS r ON r.lot_id = l.id 
			WHERE l.id = $id";

		$result_lotdetail = mysqli_query($link, $sql_lotdetail);
		$content_lot = mysqli_fetch_assoc($result_lotdetail); 
		
		if(!$content_lot){
			$error = 'Лот не найден в БД';
			$lot_content = include_template('error_lot.php', ['error' => $error]);			
		}
		else{
			$sql_category = "SELECT * FROM categories";
			$result_category = mysqli_query($link, $sql_category);
			$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

			$lot_content = include_template('lotdetail.php', 
    			[
        			'content_lot' => $content_lot,
        			'is_auth' => $is_auth,
        			'user_name' => $user_name,
        			'category' => $category
    			]);
		}
	}
}

print($lot_content);

?>