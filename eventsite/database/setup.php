<?php

/**
 * Maakt de SQLite database aan en vult de events-tabel.
 * Draaien vanaf de command line:  php database/setup.php
 */

$databaseFile = __DIR__ . '/events.sqlite';
$schemaFile   = __DIR__ . '/schema.sql';

// Verbinding maken met SQLite (bestand wordt aangemaakt als het nog niet bestaat)
$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// De SQL uit schema.sql uitvoeren
$sql = file_get_contents($schemaFile);
$pdo->exec($sql);

echo "Database klaar: $databaseFile\n";
