<?php

/**
 * @param int $initPrice Начальная цена
 * @return int $initPrice Отформатированная начальная цена, добавляется ' ₽' в случае, если цена более 1000 и отделяются 3 последние цифры пробелом 
*/
function initPrice(int $initPrice)
{
    $initPrice = ceil($initPrice);

    if ($initPrice >= 1000) {
        $initPrice = number_format($initPrice, 0, '', ' ');
        $initPrice .= ' ₽';
    }

    return $initPrice;
}


/**
 * @param string $data Время завершения торгов по лоту
 * @return string $Time Время до окончания торгов по лоту в удобночитаемом формате
*/
function TimeRate(string $data) {
    $timeStRate = $data;
    $RateDate = strtotime($timeStRate);
    $InitDay = strtotime('now 00:00:00');
    $CurrentData = strtotime('now');
    $Delta = $CurrentData - $RateDate;

    if (($CurrentData - $InitDay + 24*3600) < $Delta) {
        $Time = strstr($timeStRate, ' ', true).' в '.strstr($timeStRate, ' ', false);
    } else if (($CurrentData - $InitDay) < $Delta) {
        $Time = 'Вчера, в '.strstr($timeStRate, ' ', false);
    } else if (floor($Delta/3600) == 0) {
        $Time = floor($Delta/60);
        $Time = $Time.' '.get_noun_plural_form($Time, 'минута', 'минуты', 'минут').' назад';
    } else if(floor($Delta/3600) > 0) {
        $Time = floor($Delta/3600);
        $Time = $Time.' '.get_noun_plural_form($Time, 'час', 'часа', 'часов').' назад';        
    }

    return $Time;
}

/**
 * @param string $TimeEndOfLot Время завершения торгов по лоту
 * @return string $timer Время до окончания торгов по лоту
*/
function endSaleTimer(string $TimeEndOfLot) {

    $CurrentData = strtotime('now');
    $EndOfLot = strtotime($TimeEndOfLot);
    $Delta = $EndOfLot - $CurrentData;

	$Hours = floor($Delta/3600);
    $Minutes = floor(($Delta - $Hours*3600)/60);
    $Seconds = $Delta - $Hours*3600 - $Minutes*60;

    if ($Seconds<10) {
        $Seconds = '0'.$Seconds;
    }
    if ($Hours<10) {
        $Hours = '0'.$Hours;
    }
    if ($Minutes<10) {
        $Minutes = '0'.$Minutes;
    }
    $timer = $Hours.':'.$Minutes.':'.$Seconds;
    return $timer;
}

/**
 * @param string $TimeEndOfLot Время завершения торгов по лоту
 * @return string Имя класса в зависимости от времени
*/
function endSaleTimerHour(string $TimeEndOfLot) {
    $CurrentData = strtotime('now');
    $EndOfLot = strtotime($TimeEndOfLot);
    $Delta = $EndOfLot - $CurrentData;

    $Hours = floor($Delta/3600);
	$Class = '';
	if ($Hours<1) {
		$Class = 'timer--finishing';
	}
    return $Class;
}

function CheckUrl() {
    $page_url = $_SERVER['REQUEST_URI'];
    $main_class;
    if ($page_url == "/" || $page_url == "/index.php" ) {
        $main_class = "container";
    }
    echo $main_class;
}

/**
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 * @return array Результат запроса, массив с данными
*/
function db_fetch_data_array(mysqli $link, string $sql, array $data = []) {
   $result = [];
   $stmt = db_get_prepare_stmt($link, $sql, $data);
   mysqli_stmt_execute($stmt);
   $res = mysqli_stmt_get_result($stmt);
   if ($res) {
       $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
   }
   return $result;
}

/**
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 * @return array Результат запроса, масив с одним элементом
*/
function db_fetch_data_row(mysqli $link, string $sql, array $data = []) {
   $result = [];
   $stmt = db_get_prepare_stmt($link, $sql, $data);
   mysqli_stmt_execute($stmt);
   $res = mysqli_stmt_get_result($stmt);
   if ($res) {
       $result = mysqli_fetch_assoc($res);
   }
   return $result;
}

/**
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 * @return boolean
*/
function db_insert_data(mysqli $link, string $sql, array $data = []) {
   $stmt = db_get_prepare_stmt($link, $sql, $data);
   $result = mysqli_stmt_execute($stmt);
   if ($result) {
       $result = mysqli_insert_id($link);
   }
   return $result;
}

/**
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 * @return boolean
*/
function db_update_data(mysqli $link, string $sql, array $data = []) {
   $stmt = db_get_prepare_stmt($link, $sql, $data);
   $result = mysqli_stmt_execute($stmt);
   return $result;
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idLot ID Лота
 * @return array Данные по лоту по его ID
*/
function getLotById(mysqli $connect, int $idLot) {
    $id = $idLot;
    $link = $connect;
    $sqlLot = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, l.end_lot_time end_time, c.name category,  MAX(r.rate_price) rate_price
    FROM lot AS l 
    INNER JOIN categories as c ON l.category_id = c.id
    INNER JOIN rate AS r ON r.lot_id = l.id 
    WHERE l.id = ?
    GROUP BY l.id";

    return db_fetch_data_row($link, $sqlLot, [$id]);
}


/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idLot ID Лота
 * @return array Строка с минимальной ставкой (начальной ценой) по ID лота
*/
function getMinRate(mysqli $connect, int $idLot) {
    $id = $idLot;
    $link = $connect;
    $sqlMinRate = "SELECT lot_id, user_id, MIN(rate_price) AS rate_price
    FROM rate
    WHERE lot_id = ?
    GROUP BY lot_id, user_id";

    return db_fetch_data_row($link, $sqlMinRate, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idLot ID Лота
 * @param int $minRate Размер минимальной ставки
 * @return array Массив с данными по минимальной ставке
*/
function getRateById(mysqli $connect, int $idLot, int $minRate) { 
    $id = $idLot;
    $rate = $minRate;
    $link = $connect; 
    $sqlRates = "SELECT r.dt_rate, r.user_id, r.rate_price, r.lot_id, u.name
    FROM rate AS r
    INNER JOIN user AS u ON r.user_id = u.id
    WHERE r.lot_id = ? AND r.rate_price != ?
    ORDER BY r.dt_rate DESC";

    return db_fetch_data_array($link, $sqlRates, [$id, $rate]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $search Строка поиска
 * @param int $page_items Количество лотов на странице
 * @param int $offset Оффсет для пагинации
 * @return array Массив с данными по введенному запросу.
*/
function getSearch(mysqli $connect, string $search, int $page_items, int $offset){
    $link = $connect;
    $sData = $search;
    $items = $page_items;
    $offset = $offset;
    $sqlLots = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, l.description description, c.name category, l.end_lot_time end_time
    FROM lot as l 
    INNER JOIN categories as c ON l.category_id = c.id 
    WHERE MATCH (l.name, l.description) AGAINST(?)
    ORDER BY l.id DESC
    LIMIT $items OFFSET $offset";

    return db_fetch_data_array($link, $sqlLots, [$sData]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $userId ID пользователя
 * @return array Массив с данными о ставках, сделанных пользователем, за исключением начальной цены (минимальной ставки, которая записывается при добавлении лота)
*/
function getRateByUser(mysqli $connect, int $userId) {
    $link = $connect;
    $id = $userId;
    $sqlBest = 
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

    return db_fetch_data_array($link, $sqlBest, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idCatgory ID категории
 * @return array Массив с данными о лоте по данной категории
*/
function getLotsByCategory(mysqli $connect, int $idCatgory) {
    $id = $idCatgory;
    $link = $connect;
    $sqlLot = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, l.end_lot_time end_time, c.name category,  MAX(r.rate_price) rate_price
    FROM lot AS l 
    INNER JOIN categories as c ON l.category_id = c.id
    INNER JOIN rate AS r ON r.lot_id = l.id 
    WHERE l.category_id = ?
    GROUP BY l.id";

    return db_fetch_data_array($link, $sqlLot, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idCatgory ID категории
 * @param int $page_items к-во лотов на странице
 * @param int $offset для пагинации
 * @return array Массив с данными о лоте по данной категории, подготовленный для пагинации
*/
function getLotsByLimit(mysqli $connect, int $idCategory, int $page_items, int $offset) {
    $id = $idCategory;
    $link = $connect;
    $items = $page_items;
    $offset = $offset;
    $sqlLot = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, l.category_id, c.name category, l.end_lot_time end_time 
                FROM lot as l 
                INNER JOIN categories as c 
                ON l.category_id = c.id
                WHERE l.category_id = ?
                LIMIT $items OFFSET $offset";
    return db_fetch_data_array($link, $sqlLot, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param $loginInfoEmail string 
 * @return array Данные о пользователе, полученные на основе информации о Email
*/
function getInfoUserByEmail(mysqli $connect, string $loginInfoEmail) {
    $link = $connect;
    $email = $loginInfoEmail;
    $sqlCheckEmail = "SELECT * FROM user WHERE email = ?";

    return db_fetch_data_row($link, $sqlCheckEmail, [$email]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $Id ID пользователя
 * @return array Данные о пользователе, полученные на основе информации об ID
*/
function getInfoUserById(mysqli $connect, int $Id) {
    $link = $connect;
    $id = $Id;
    $sql = "SELECT * FROM user WHERE id = ?";

    return db_fetch_data_row($link, $sql, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $name Имя пользователя
 * @return array Данные о пользователе, полученные на основе информации об ID
*/
function getCategoryByName(mysqli $connect, string $name) {
    $link = $connect;
    $nameCat = $name;
    $sqlCat = "SELECT id FROM categories WHERE name = ?";

    return db_fetch_data_row($link, $sqlCat, [$nameCat]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @return array Все категории
*/
function getAllCategory(mysqli $connect) {
    $link = $connect;
    $sqlCategory = "SELECT * FROM categories";
    $resultCategory = mysqli_query($link, $sqlCategory);
    if ($resultCategory) {
        return mysqli_fetch_all($resultCategory, MYSQLI_ASSOC);
    }

    return [];
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $categoryId ID категории 
 * @return array Данные о категории, полученные на основе информации об ID категории
*/
function getCategoryById(mysqli $connect, int $categoryId) {
    $link = $connect;
    $id = $categoryId;
    $sqlCategoryById = "SELECT * FROM categories WHERE id = ?";

    return db_fetch_data_row($link, $sqlCategoryById, [$id]);
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @return array Все лоты
*/
function getAllLots(mysqli $connect) {
    $link = $connect;
    $sqlLots = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, c.name category, l.end_lot_time end_time FROM lot as l INNER JOIN categories as c ON l.category_id = c.id ORDER BY l.id DESC";
    $resultLots = mysqli_query($link, $sqlLots);
    if ($resultLots) {
        return mysqli_fetch_all($resultLots, MYSQLI_ASSOC);
    }   

    return [];
}

/**
 * @param string $txtError Текст ошибки
 * @return string Контент страницы в случае ошибки
*/
function selectDbError(string $txtError) {
    $txt = $txtError;
    $content = include_template('error.php',['text'  => $txt]);
    return include_template('layout.php', ['content' => $content]);    
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $txtError Текст ошибки, дополнительный к тому, что выведется от самой СУБД при ошибке соединения с БД 
*/
function сonnectDbError(mysqli $connect, string $txtError) {
    $link = $connect;
    $txt = $txtError;
    $error = mysqli_connect_error($link);
    $content = include_template('error.php',['text'  => $txt, 'error' => $error]);
    $layout_content = include_template('layout.php', ['content' => $content]);
    print($layout_content);
    exit;
}

/**
 * @param string $txtError Текст ошибки + к коду 404
 * @param string $title - заголовок страницы
 * @param mysqli $connect Ресурс соединения с БД
*/
function error404(mysqli $connect, string $txtError, string $title) {
    http_response_code(404);
    $error = http_response_code();
    $txt = $txtError;
    $link = $connect;
    $content = include_template('error.php',['text'  => $txt, 'error' => $error]);
    $layout_content = include_template('layout.php', ['content' => $content, 'title' => $title, 'category' => $category = getAllCategory($link)]);
    print($layout_content);
    exit;
}

/**
 * @param string $time Дата завершения торгов
 * @return string $timeEnd Дата завершения торгов, если выполняется условие, что введенная дата больше текушей + 24 часа
*/
function checkEndTimeLot(string $time) {
    $timeEnd = $time;
    $endTimeTS = strtotime($timeEnd);
    $initDay = strtotime('now');
    $lastDay = $initDay + 24*3600;

    if ($endTimeTS > $lastDay) {
        return $timeEnd;
    } 

    return $timeEnd = 'error';
}

/**
 * @param mysqli $connect Ресурс соединения с БД
 * @return array Массив с победителями
*/
function getWinnersArray(mysqli $connect) {
    $link = $connect;

    /*Нижняя часть запроса - исключаем лоты, по которым не было сделано ставок. т.е у них только начальная цена.*/

    $sql = "
    SELECT z.lot_id, z.user_id FROM
        (SELECT rates.lot_id, rates.user_id FROM
            (SELECT id FROM lot WHERE uservictory_id IS NULL AND end_lot_time <= NOW()) AS lots
            INNER JOIN 
                (SELECT rt.user_id, rt.lot_id, rt.rate_price FROM rate AS rt 
                INNER JOIN 
                (SELECT MAX(rate_price) AS rate, lot_id FROM rate GROUP BY lot_id) AS r 
                ON rt.lot_id = r.lot_id AND rt.rate_price = r.rate) AS rates
            ON lots.id = rates.lot_id) AS z
        INNER JOIN
        (SELECT r.lot_id as lot_id FROM 
            (SELECT COUNT(rate_price) AS c_rate, lot_id FROM rate r
            GROUP BY lot_id) AS r
            WHERE r.c_rate != 1) AS p
    ON z.lot_id = p.lot_id";


    $resultWinner = mysqli_query($link, $sql);
    if ($resultWinner) {
        return mysqli_fetch_all($resultWinner, MYSQLI_ASSOC);
    }

    return [];
}

/**
 * @param string $timeEndLot Дата завершения торгов
 * @return string $timeEnd. Проверка ведутся ли торги по лоту (дата окончния <= текущей даты)
*/
function endLot($timeEndLot) {
    $timeEnd = $timeEndLot;
    $endTimeTS = strtotime($timeEnd);
    $now = strtotime('now');

    if ($endTimeTS > $now) {
        return endSaleTimer($timeEnd);
    } 
    return  'Торги окончены';         
}

/**
 * @param string $str Строка
 * @return int $data 
*/
function esc($str){
    $data = htmlspecialchars($str);
    return $data;
}
?>