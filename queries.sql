/*Добавление категории*/
INSERT INTO categories (name) 
VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

/*Добавление пользователей*/
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

/*Добавление лотов*/
INSERT INTO lot (dt_add, name, description, img_path, init_price, step_rate, category_id, usercreate_id, uservictory_id)
VALUES 
(
	NOW(), 
	'2014 Rossignol District Snowboard', 
	'Описание лота 1', 
	'img/lot-1.jpg', 
	'10999', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Доски и лыжи'), 
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru')
),
(
	NOW(), 
	'DC Ply Mens 2016/2017 Snowboard', 
	'Описание лота 2', 
	'img/lot-2.jpg', 
	'159999', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Доски и лыжи'), 
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru')
),
(
	NOW(), 
	'Крепления Union Contact Pro 2015 года размер L/XL', 
	'Описание лота 3', 
	'img/lot-3.jpg', 
	'8000', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Крепления'), 
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru')
),
(
	NOW(), 
	'Ботинки для сноуборда DC Mutiny Charocal', 
	'Описание лота 4', 
	'img/lot-4.jpg', 
	'10999', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Ботинки'), 
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru')
),
(
	NOW(), 
	'Куртка для сноуборда DC Mutiny Charocal', 
	'Описание лота 5', 
	'img/lot-5.jpg', 
	'7500', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Одежда'), 
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru')
),
(
	NOW(), 
	'Маска Oakley Canopy', 
	'Описание лота 6', 
	'img/lot-6.jpg', 
	'5400', 
	'100', 
	(SELECT id FROM categories WHERE name = 'Разное'), 
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru'),
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru')
);

/*Добавление ставок*/
INSERT INTO rate (dt_rate, rate_price, user_id, lot_id)
VALUES 
(
	NOW(), 
	'20000',
	(SELECT id FROM user WHERE email = 'ap_job@mail.ru'), 
	(SELECT id FROM lot WHERE name = 'Куртка для сноуборда DC Mutiny Charocal')
),
(
	NOW(), 
	'30000',
	(SELECT id FROM user WHERE email = 'apl_job@mail.ru'), 
	(SELECT id FROM lot WHERE name = 'Ботинки для сноуборда DC Mutiny Charocal')
);

/*получить все категории*/
SELECT name FROM categories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, 
ссылку на изображение, цену, название категории;*/

SELECT l.NAME, l.init_price, l.img_path, c.NAME category_name
	FROM lot as l INNER JOIN categories as c 
	ON l.category_id = c.id ORDER BY l.id DESC


/*показать лот по его id. Получите также название категории, к которой принадлежит лот;*/
SELECT l.NAME lot_name, c.NAME category_name
	FROM lot as l INNER JOIN categories as c 
	ON l.category_id = c.id AND l.id='1';

/*обновить название лота по его идентификатору;*/
UPDATE lot
	SET name = 'Samsung Galaxy'
	WHERE id='2';

/*получить список самых свежих ставок для лота по его идентификатору.*/
SELECT * FROM rate WHERE lot_id='5' ORDER BY id DESC;