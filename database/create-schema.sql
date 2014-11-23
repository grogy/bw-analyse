CREATE DATABASE IF NOT EXISTS wikipedia;

USE wikipedia;


CREATE TABLE articles (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) TYPE=innodb;


CREATE TABLE categories (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) TYPE=innodb;


CREATE TABLE portals (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) TYPE=innodb;


CREATE TABLE article_portals (
	article_id INT NOT NULL,
	portal_id INT NOT NULL,
	FOREIGN KEY (article_id) REFERENCES articles(id),
	FOREIGN KEY (portal_id) REFERENCES portals(id)
) TYPE=innodb;


CREATE TABLE article_categories (
	article_id INT NOT NULL,
	category_id INT NOT NULL,
	FOREIGN KEY (article_id) REFERENCES articles(id),
	FOREIGN KEY (category_id) REFERENCES categories(id)
) TYPE=innodb;
