<?php
declare(strict_types=1);

namespace App\Config;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Geeft de gedeelde PDO-verbinding terug en maakt die de eerste keer aan.
     *
     * @return PDO de verbinding met de SQLite-database
     */
    public static function getInstance(): PDO
    {
        // verbinding 1x opzetten en daarna hergebruiken
        if (self::$pdo === null) {
            $path = dirname(__DIR__, 2) . '/' . $_ENV['DB_PATH'];

            self::$pdo = new PDO('sqlite:' . $path);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$pdo;
    }
}
