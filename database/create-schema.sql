DROP DATABASE IF EXISTS wikipedia;
CREATE DATABASE wikipedia
CHARACTER SET utf8 COLLATE utf8_general_ci;


USE wikipedia;


CREATE TABLE articles (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) ENGINE=InnoDB;


CREATE TABLE categories (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) ENGINE=InnoDB;


CREATE TABLE portals (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255)
) ENGINE=InnoDB;


CREATE TABLE article_portals (
	article_id INT NOT NULL,
	portal_id INT NOT NULL,
	FOREIGN KEY (article_id) REFERENCES articles(id),
	FOREIGN KEY (portal_id) REFERENCES portals(id)
) ENGINE=InnoDB;


CREATE TABLE article_categories (
	article_id INT NOT NULL,
	category_id INT NOT NULL,
	FOREIGN KEY (article_id) REFERENCES articles(id),
	FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;
