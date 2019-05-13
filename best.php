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

$userID = $_SESSION['userid'];
/* Узнаем количество ставок по текущему $userID, исключая из этого списка начальные цены лотов (минимальные ставки в табл. rate, которые записываются в таблице rate при добавлении нового лота)*/
$sql_best = 
"SELECT A.NAME, A.rate_price, A.id, A.url, A.category, A.dt, A.rate_price, A.end_time  FROM(
		SELECT l.id id, l.NAME name, l.img_path url, c.name category, r.dt_rate dt, r.rate_price rate_price, l.end_lot_time end_time
		FROM lot AS l 
		INNER JOIN categories as c ON l.category_id = c.id
		INNER JOIN rate AS r ON r.lot_id = l.id 
		WHERE r.user_id = ?
		ORDER BY r.dt_rate DESC) AS A
INNER JOIN(
		SELECT lot_id,  MIN(rate_price) AS rate_price
		FROM rate
		GROUP BY lot_id) AS B
ON A.id = B.lot_id AND A.rate_price != B.rate_price
ORDER BY A.dt DESC";

$result = mysqli_prepare($link, $sql_best);
$stmt = db_get_prepare_stmt($link, $sql_best, [$_SESSION['userid']]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$best = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql_category = "SELECT * FROM categories";
$result_category = mysqli_query($link, $sql_category);
$category = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

$content = include_template('best.php', [
	'category' => $category,
	'best' => $best,
	'minRates' => $minRates
]);

$layout_content = include_template('layout.php', 
	[
		'content' => $content,
		'title' => 'Мои ставки',
		'category' => $category
	]);

print($layout_content);

?>