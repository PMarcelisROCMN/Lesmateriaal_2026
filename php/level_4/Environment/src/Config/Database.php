<?php

class Database {

    public static PDO $pdo;

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

private string $host     = $_ENV['DB_HOST'];
private string $dbname   = $_ENV['DB_NAME'];
private string $username = $_ENV['DB_USER'];
private string $password = $_ENV['DB_PASS'];


    // get instance
    public static function getInstance(): PDO {
        if (!isset(self::$pdo)) {
            self::$pdo = new PDO('sqlite:database.db');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}