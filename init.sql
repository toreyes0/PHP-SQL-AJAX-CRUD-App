CREATE DATABASE IF NOT EXISTS test;

USE test;

CREATE TABLE IF NOT EXISTS products(
	id INT NOT NULL AUTO_INCREMENT,
	product_name VARCHAR(30) NOT NULL,
    unit VARCHAR(30) NOT NULL,
    price DEC(10,2) NOT NULL,
    date_of_expiry DATE NOT NULL,
    available_inventory INT NOT NULL,
    available_inventory_cost DOUBLE NOT NULL,
    image LONGTEXT NOT NULL,
    PRIMARY KEY(id)
);
