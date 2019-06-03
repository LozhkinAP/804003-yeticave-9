<?php
/**
 * Фунция добавляет ' ₽' в случае, если цена более 1000 и отделяются 3 последние цифры пробелом 
 *
 * @param int $initPrice Начальная цена
 *
 * @return int $initPrice Отформатированная начальная цена
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
 * Функция возвращает время до окончания торгов по лоту в удобночитаемом формате
 *
 * @param string $data Время завершения торгов по лоту
 *
 * @return string $Time Время 
*/
function time_rate(string $data) {
    $timeStRate = $data;
    $RateDate = strtotime($timeStRate);
    $InitDay = strtotime('now 00:00:00');
    $CurrentData = strtotime('now');
    $Delta = $CurrentData - $RateDate;

    if (($CurrentData - $InitDay + 24*3600) < $Delta) {
        $Time = strstr($timeStRate, ' ', true).' в '.strstr($timeStRate, ' ', false);
    } else if (($CurrentData - $InitDay) < $Delta) {
        $Time = 'Вчера, в '.strstr($timeStRate, ' ', false);
    } else if (floor($Delta/3600) === 0.0) {
        $Time = floor($Delta/60);
        $Time = $Time.' '.get_noun_plural_form($Time, 'минута', 'минуты', 'минут').' назад';
    } else if(floor($Delta/3600) > 0.0) {
        $Time = floor($Delta/3600);
        $Time = $Time.' '.get_noun_plural_form($Time, 'час', 'часа', 'часов').' назад';        
    }

    return $Time;
}

/**
 * Функция возвращает значение времени, оставшегося до завершения торгов по лоту
 *  
 * @param string $TimeEndOfLot Время завершения торгов по лоту
 * 
 * @return string $timer Время 
*/
function end_sale_timer(string $TimeEndOfLot) {

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
 * Функция возвращает имя класса 'timer--finishing', если до окончания торгов менее 1 часа
 *
 * @param string $TimeEndOfLot Время завершения торгов по лоту
 *
 * @return string $Class Имя класса 
*/
function end_sale_timer_hour(string $TimeEndOfLot) {
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

/**
 * Функция возвращает имя класса 'container', если мы находимся на главной странице. 
 *
 * @param string $pageUrl Время завершения торгов по лоту
 *
 * @return string $main_class Имя класса
*/
function add_class_container(string $pageUrl) {
    $main_class = '';
    if ($pageUrl === "/" || $pageUrl === "/index.php") {
        return $main_class = 'container';
    }
    return $main_class;
}


/**
 * Функция выполняет выборку из БД по указанному sql-запросу, возвращая массив с данными
 * 
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 * 
 * @return array Массив с данными
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
 * Функция выполняет выборку из БД по указанному sql-запросу, в случае, когда элемент один. 
 *
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 *
 * @return array Массив с одним элементом
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
 * Функция выполняет вставку в БД по указанному sql-запросу, возвращает true - если успешно, false - если нет.
 *
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - данные для запроса SQL
 *
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
 * Функция обновление данных в таблице по указанному sql-запросу, возвращает true - если успешно, false - если нет.
 *
 * @param mysqli $link Ресурс соединения с БД
 * @param string $sql Строка запроса SQL
 * @param array $data = [] - Данные для запроса SQL
 *
 * @return boolean
*/
function db_update_data(mysqli $link, string $sql, array $data = []) {
   $stmt = db_get_prepare_stmt($link, $sql, $data);
   $result = mysqli_stmt_execute($stmt);
   return $result;
}

/**
 * Функция получает данные по лоту по его ID
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $id ID Лота
 *
 * @return array 
*/
function get_lot_by_id(mysqli $connect, int $id) {
    $sql = "SELECT l.id id, l.name name, l.dt_add dt, l.init_price price, l.img_path url, l.description description, l.step_rate step_rate, l.usercreate_id usercreate_id, l.end_lot_time end_time, c.name category, MAX(r.rate_price) rate_price
            FROM lot AS l 
                INNER JOIN categories as c ON l.category_id = c.id
                INNER JOIN rate AS r ON r.lot_id = l.id 
            WHERE l.id = ?
            GROUP BY l.id";

    return db_fetch_data_row($connect, $sql, [$id]);
}

/**
 * Функция добавляет новую ставку для лота.
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $cost минимаьная ставка
 * @param int $userId ID пользователя
 * @param int $lotId ID лота
 *
 * @return boolean
*/
function add_rate_by_lot(mysqli $connect, int $cost, int $userId, int $lotId) {
    $sql = "INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
            VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($connect, $sql, [$cost, $userId, $lotId]);
    return mysqli_stmt_execute($stmt);
}

/**
 * Функция получает ставки по указанному ID лота, за исключением минимальной ставки, т.к. это начальная цена лота.
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $id ID лота
 *
 * @return array 
*/
function get_rate_by_lot_id(mysqli $connect, int $id) {
    $sql = "SELECT r.dt_rate, r.user_id, r.rate_price, r.lot_id, u.name
            FROM rate AS r
            INNER JOIN user AS u ON r.user_id = u.id
            WHERE r.lot_id = ? AND r.rate_price != (SELECT MIN(r.rate_price) FROM rate AS r
                                                    WHERE r.lot_id = ?
                                                    GROUP BY r.lot_id)
            ORDER BY r.dt_rate DESC";

    return db_fetch_data_array($connect, $sql, [$id, $id]);
}

/**
 * Функция получает ID пользователя, сделавшего последнюю ставку
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $id ID лота
 *
 * @return array
*/
function last_rate_user(mysqli $connect, int $id) {
    $sql = "SELECT user_id 
            FROM rate
            WHERE lot_id = ? AND rate_price = (SELECT MAX(rate_price) rate_price
                                                FROM rate
                                                WHERE lot_id = ?)";

    return db_fetch_data_row($connect, $sql, [$id, $id]);
}

/**
 * Функция получает данные по введенному поисковому запросу.
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $search Строка поиска
 * @param int $page_items Количество лотов на странице
 * @param int $offset Оффсет для пагинации
 *
 * @return array 
*/
function get_search(mysqli $connect, string $search, int $page_items, int $offset){
    $sql = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, l.description description, c.name category, l.end_lot_time end_time
            FROM lot as l 
            INNER JOIN categories as c ON l.category_id = c.id 
            WHERE MATCH (l.name, l.description) AGAINST(?)
            ORDER BY l.id DESC
            LIMIT $page_items OFFSET $offset";

    return db_fetch_data_array($connect, $sql, [$search]);
}


/**
 * Функция получает информацию о ставках, сделанных пользователем, за исключением начальной цены (минимальной ставки, которая записывается при добавлении лота). Используется для отображения информации на странице "Мои ставки"
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $userId ID пользователя
 *
 * @return array
*/
function get_rate_by_user(mysqli $connect, int $userId) {
    $sql = 
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

    return db_fetch_data_array($connect, $sql, [$userId]);
}

/**
 * Функция получает массив с данными о лоте по указанной категории, подготовленный для пагинации
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $idCatgory ID категории
 * @param int $page_items к-во лотов на странице
 * @param int $offset для пагинации
 *
 * @return array 
*/
function get_lots_by_limit(mysqli $connect, int $idCategory, int $page_items, int $offset) {
    $sql = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, l.category_id, c.name category, l.end_lot_time end_time 
                FROM lot as l 
                INNER JOIN categories as c 
                ON l.category_id = c.id
                WHERE l.category_id = ?
                LIMIT $page_items OFFSET $offset";
    return db_fetch_data_array($connect, $sql, [$idCategory]);
}

/**
 * Функция получает данные о пользователе, полученные на основе информации о его Email
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param $loginInfoEmail string 
 *
 * @return array 
*/
function get_info_user_by_email(mysqli $connect, string $loginInfoEmail) {
    $sql = "SELECT * FROM user WHERE email = ?";

    return db_fetch_data_row($connect, $sql, [$loginInfoEmail]);
}

/**
 * Функция получает данные о пользователе, полученные на основе информации о его ID
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $Id ID пользователя
 *
 * @return array
*/
function get_info_user_by_id(mysqli $connect, int $idUser) {
    $sql = "SELECT * FROM user WHERE id = ?";

    return db_fetch_data_row($connect, $sql, [$idUser]);
}

/**
 * Функция получает данные о категории, полученные на основе информации о ее имени
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $name Имя категории
 *
 * @return array 
*/
function get_category_by_name(mysqli $connect, string $name) {
    $sql = "SELECT * FROM categories WHERE name = ?";
    return db_fetch_data_row($connect, $sql, [$name]);
}

/**
 * Функция получает данные о всех имеющихся категориях
 *
 * @param mysqli $connect Ресурс соединения с БД
 *
 * @return array 
*/
function get_all_category(mysqli $connect) {
    $sql = "SELECT * FROM categories";
    $res = mysqli_query($connect, $sql);
    if ($res) {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return [];
}

/**
 * Функция получает данные о категории, полученные на основе информации об ID категории
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param int $categoryId ID категории
 * 
 * @return array 
*/
function get_cat_by_id(mysqli $connect, int $categoryId) {
    $sql = "SELECT * FROM categories WHERE id = ?";

    return db_fetch_data_row($connect, $sql, [$categoryId]);
}


function get_all_lots(mysqli $connect) {
    $sql = "SELECT l.id id, l.name name, l.init_price price, l.img_path url, c.name category, l.end_lot_time end_time FROM lot as l INNER JOIN categories as c ON l.category_id = c.id ORDER BY l.id DESC";
    $result = mysqli_query($connect, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }   

    return [];
}

/**
 * Функция возвращает контент страницы в случае ошибки
 *
 * @param string $txtError Текст ошибки
 *
 * @return string 
*/
function select_db_error(string $txtError) {
    $txt = $txtError;
    $content = include_template('error.php',['text'  => $txt]);
    return include_template('layout.php', ['content' => $content]);    
}

/**
 * Функция получает контент страницы в случае ошибки ошибке соединения с БД 
 *
 * @param mysqli $connect Ресурс соединения с БД
 * @param string $txtError, текст, дополнительный к тексту, полученому от mysqli_connect_error()
*/
function connect_db_error(mysqli $connect, string $txtError) {
    $txt = $txtError;
    $error = mysqli_connect_error($connect);
    $content = include_template('error.php',['text'  => $txt, 'error' => $error]);
    $layout_content = include_template('layout.php', ['content' => $content]);
    print($layout_content);
    exit;
}

/**
 * Функция получает контент страницы в случае ошибки 404
 *
 * @param string $txtError Текст ошибки + к коду 404
 * @param string $title - заголовок страницы
 * @param mysqli $connect Ресурс соединения с БД
*/
function error404(mysqli $connect, string $txtError, string $title) {
    http_response_code(404);
    $error = http_response_code();
    $txt = $txtError;
    $content = include_template('error.php',['text'  => $txt, 'error' => $error]);
    $layout_content = include_template('layout.php', ['content' => $content, 'title' => $title, 'category' => $category = getAllCategory($connect)]);
    print($layout_content);
    exit;
}


/**
 * Функция проверяет корректно ли введено значение даты окончания торгов. Должно быть - текущий день + 24часа
 *
 * @param string $time 
 *
 * @return string 
*/
function check_end_time_lot(string $time) {
    $endTimeTS = strtotime($time);
    $initDay = strtotime('now');
    $lastDay = $initDay + 24*3600;

    if ($endTimeTS > $lastDay) {
        return $time;
    } 

    return $time = 'error';
}

/**
 * Функция получает массив с победителями
 *
 * @param mysqli $connect Ресурс соединения с БД
 *
 * @return array Массив с победителями
*/
function get_win_array(mysqli $connect) {
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


    $result = mysqli_query($connect, $sql);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return [];
}

/**
 * Функция проверяет ведутся ли торги по лоту (дата окончния <= текущей даты). 
 *
 * @param string $timeEndLot Дата завершения торгов
 *
 * @return string $timeEnd. 
*/
function end_lot($timeEndLot) {
    $endTimeTS = strtotime($timeEndLot);
    $now = strtotime('now');

    if ($endTimeTS > $now) {
        return end_sale_timer($timeEndLot);
    } 
    return  'Торги окончены';         
}

/**
 * Для сокращенной записи функции htmlspecialchars.
 *
 * @param string $str Строка
 *
 * @return $data 
*/
function esc($str){
    $data = htmlspecialchars($str);
    return $data;
}
?>