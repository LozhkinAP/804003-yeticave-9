/*Добавление категории*/
INSERT INTO categories (name, scode) 
VALUES 
(
	'Доски и лыжи',
	'boards'
), 
(
	'Крепления',
	'attachment'
), 
(
	'Ботинки',
	'boots'
), 
(
	'Одежда',
	'clothing'
), 
(
	'Инструменты',
	'tools'
), 
(
	'Разное',
	'other'
);


/* ДЛЯ НАСТАВНИКА:
	Для проверки задания после создания схемы БД выполняйте, пожалуйста, только SQL-запрос на добавление категорий (выше).
	Лоты добавляйте через интерфейс проекта.
	Пользователей создавайте через интерфейс проекта
	Ставки по лотам делайте в карточке лота. 
*/


/*Добавление пользователей
INSERT INTO user (dt_add, email, pass, name, avatar_path, contacts)
VALUES 
(
	NOW(), 
	'apl_job@mail.ru', 
	'12345', 
	'ara', 
	'img/ava1.png', 
	'Msk'
),
(
	NOW(), 
	'ap_job@mail.ru', 
	'1234', 
	'sara', 
	'img/ava2.png', 
	'Spb'
);

Добавление лотов
INSERT INTO lot (dt_add, name, description, img_path, init_price, step_rate, category_id, end_lot_time, usercreate_id, uservictory_id)
VALUES 
(
	NOW(), 
	'2014 Rossignol District Snowboard', 
	'Описание лота 1', 
	'img/lot-1.jpg', 
	10999, 
	100, 
	1,
	NOW(),
	1,
	1
),
(
	NOW(), 
	'DC Ply Mens 2016/2017 Snowboard', 
	'Описание лота 2', 
	'img/lot-2.jpg', 
	159999, 
	100, 
	2, 
	NOW(),
	2,
	2
),
(
	NOW(), 
	'Крепления Union Contact Pro 2015 года размер L/XL', 
	'Описание лота 3', 
	'img/lot-3.jpg', 
	8000, 
	100, 
	4, 
	NOW(),
	1,
	1
),
(
	NOW(), 
	'Ботинки для сноуборда DC Mutiny Charocal', 
	'Описание лота 4', 
	'img/lot-4.jpg', 
	10999, 
	100, 
	3, 
	NOW(),
	1,
	2
),
(
	NOW(), 
	'Куртка для сноуборда DC Mutiny Charocal', 
	'Описание лота 5', 
	'img/lot-5.jpg', 
	7500, 
	100, 
	5, 
	NOW(),
	1,
	1
),
(
	NOW(), 
	'Маска Oakley Canopy', 
	'Описание лота 6', 
	'img/lot-6.jpg', 
	5400, 
	100, 
	6, 
	NOW(),
	2,
	1
);

Добавление ставок
INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
VALUES 
(
	NOW(), 
	20000,
	1, 
	1
),
(
	NOW(), 
	30000,
	2, 
	2
),
(
	NOW(), 
	30000,
	2, 
	3
),
(
	NOW(), 
	30000,
	2, 
	4
),
(
	NOW(), 
	30000,
	2, 
	5
),
(
	NOW(), 
	30000,
	2, 
	6
),
(
	NOW(), 
	20000,
	1, 
	7
),
(
	NOW(), 
	30000,
	2, 
	8
),
(
	NOW(), 
	20000,
	1, 
	9
),
(
	NOW(), 
	30000,
	2, 
	10
),
(
	NOW(), 
	20000,
	1, 
	11
),
(
	NOW(), 
	30000,
	2, 
	12
);
*/
/*получить все категории*/
SELECT name FROM categories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, 
ссылку на изображение, цену, название категории;*/

SELECT l.name, l.init_price, l.img_path, c.name category_name, r.rate_price 
	FROM lot as l 
	INNER JOIN categories as c ON l.category_id = c.id 
	INNER JOIN rate AS r ON r.lot_id = l.id 
	ORDER BY l.id DESC;

/*показать лот по его id. Получите также название категории, к которой принадлежит лот;*/
SELECT l.name lot_name, c.name category_name
    FROM lot as l INNER JOIN categories as c 
    ON l.category_id = c.id AND l.id = 1;

/*обновить название лота по его идентификатору;*/
UPDATE lot
    SET name = 'Samsung Galaxy'
    WHERE id = 2;

/*получить список самых свежих ставок для лота по его идентификатору.*/
SELECT * FROM rate WHERE lot_id = 5 ORDER BY id DESC;