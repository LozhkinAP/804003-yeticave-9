CREATE DATABASE yeticavedb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

USE yeticavedb;

CREATE TABLE categories (
	id	INT	AUTO_INCREMENT,
	name char(255),
	PRIMARY KEY (id),
	INDEX (name)
);

CREATE TABLE lot (
	id	INT	AUTO_INCREMENT,
	dt_add datetime,
	name char(255) NOT NULL,
	description text NOT NULL,
	img_path char(255) NOT NULL,
	init_price int NOT NULL,
	step_rate int NOT NULL,
	category_id int,
	usercreate_id int,
	uservictory_id int,
	PRIMARY KEY (id),
	INDEX (name),
	INDEX (dt_add), 
	INDEX (init_price), 
	INDEX (category_id), 
	INDEX (usercreate_id), 
	INDEX (uservictory_id)
);

CREATE TABLE rate (
	id	INT	AUTO_INCREMENT,
	dt_rate datetime,
	rate_price int,
	user_id int,
	lot_id int,
	PRIMARY KEY (id),
	INDEX (dt_rate), 
	INDEX (user_id), 
	INDEX (lot_id)
);


CREATE TABLE user (
	id	INT	AUTO_INCREMENT,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email char(128) NOT NULL,
	pass char(64) NOT NULL,
	name char(128) NOT NULL,
	avatar_path char(255),
	contacts char(255),
	PRIMARY KEY (id),
	UNIQUE INDEX (email),
	INDEX (dt_add),
	INDEX (name)
);