<?php

namespace App\Database;

use PDO;

class Database {
    /**
     * Maakt verbinding met de SQLite database en geeft een PDO-object terug.
     */
    public static function connect(): PDO {
        $databaseFile = __DIR__ . '/../../database/events.sqlite';

        $pdo = new PDO('sqlite:' . $databaseFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
