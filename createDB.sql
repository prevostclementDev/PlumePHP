SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE external_link (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    link varchar(255) NOT NULL,
    img_link varchar(255) NOT NULL
);

CREATE TABLE site (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name varchar(255) NOT NULL,
    dir varchar(455) NOT NULL,
    url varchar(455) NOT NULL,
    categories varchar(655) NOT NULL,
    php_version varchar(255) NOT NULL
);