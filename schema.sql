CREATE DATABASE yeticavedb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE yeticavedb;

CREATE TABLE categories (
	id	INT	AUTO_INCREMENT PRIMARY KEY,
	name char(255)
);

CREATE TABLE lot (
	id	INT	AUTO_INCREMENT PRIMARY KEY,
	dt_add datetime,
	name char(255) NOT NULL,
	description char(255) NOT NULL,
	img_path char(255) NOT NULL,
	init_price int NOT NULL,
	step_rate int NOT NULL,
	categories_id int,
	usercreate_id int,
	uservictory_id int

);

CREATE TABLE rate (
	id	INT	AUTO_INCREMENT PRIMARY KEY,
	dt_rate datetime,
	rate_price int,
	user_id int,
	lot_id int
);

CREATE TABLE user (
	id	INT	AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email char(128) NOT NULL UNIQUE,
	pass char(64) NOT NULL,
	name char(128) NOT NULL UNIQUE,
	avatar_path char(255),
	contacts char(255),
	lot_id int,
	rate_id int
);
