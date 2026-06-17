<?php

namespace App\Config;

use PDO;

class Database
{
    public static PDO $pdo;

    public static function getInstance(): PDO
    {
        if (!isset(self::$pdo)) {
            self::$pdo = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
