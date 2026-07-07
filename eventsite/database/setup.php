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

// Voorbeeld-gebruikers aanmaken. We hashen de wachtwoorden hier in PHP,
// zodat er nooit een leesbaar wachtwoord in de database komt te staan.
$users = [
    // username, wachtwoord, isAdmin
    ['admin', 'admin123', 1],
    ['piet',  'piet123',  0],
];

$stmt = $pdo->prepare('INSERT INTO users (username, password, isAdmin) VALUES (?, ?, ?)');
foreach ($users as [$username, $password, $isAdmin]) {
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $isAdmin]);
}

echo "Database klaar: $databaseFile\n";
echo "Inloggen kan met: admin / admin123 (beheerder) of piet / piet123\n";
