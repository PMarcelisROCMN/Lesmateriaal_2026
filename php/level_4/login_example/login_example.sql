CREATE DATABASE IF NOT EXISTS login_example
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE login_example;

CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
