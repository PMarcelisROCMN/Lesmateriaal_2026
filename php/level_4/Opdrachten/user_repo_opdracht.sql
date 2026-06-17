-- Importeer dit bestand via phpMyAdmin: Importeren → bestand kiezen → Uitvoeren
-- Wachtwoord voor alle testgebruikers is: test1234

CREATE DATABASE IF NOT EXISTS user_repo_opdracht
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE user_repo_opdracht;

CREATE TABLE IF NOT EXISTS users (
    id       INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)   NOT NULL UNIQUE,
    email    VARCHAR(100)  NOT NULL UNIQUE,
    password VARCHAR(255)  NOT NULL
);

INSERT INTO users (username, email, password) VALUES
('peter', 'peter@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'),
('jan',   'jan@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('lisa',  'lisa@example.com',  '$2y$10$fAH6S5HXDxMvQqH0V3W8cuq8YPwHE8nHJqwFknFJHzSf8CK7a6oty');
