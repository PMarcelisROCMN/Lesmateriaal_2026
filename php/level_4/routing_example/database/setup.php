<?php
declare(strict_types=1);

// run dit 1x om de database klaar te zetten: php database/setup.php
// maakt de tabellen opnieuw aan en vult ze met voorbeelddata

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$pdo = Database::getInstance();

// schoon beginnen
$pdo->exec('DROP TABLE IF EXISTS admins');
$pdo->exec('DROP TABLE IF EXISTS events');

$pdo->exec(
    'CREATE TABLE admins (
        id       INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )'
);

$pdo->exec(
    "CREATE TABLE events (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        title       TEXT NOT NULL,
        description TEXT NOT NULL DEFAULT '',
        location    TEXT NOT NULL,
        date        TEXT NOT NULL
    )"
);

// admin-account, wachtwoord gehasht opslaan
$stmt = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
$stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);

// voorbeeld-evenementen
$events = [
    ['Open Coding Night', 'Een avond samen programmeren met pizza en muziek.', 'Mediacollege, Amsterdam', '2026-09-18'],
    ['Game Jam Weekend', 'Bouw in 48 uur een complete game met je team.', 'TechHub, Utrecht', '2026-10-03'],
    ['Webdevelopment Meetup', 'Lezingen over moderne PHP en frontend.', 'De Kantine, Rotterdam', '2026-11-12'],
];

$stmt = $pdo->prepare('INSERT INTO events (title, description, location, date) VALUES (?, ?, ?, ?)');
foreach ($events as $event) {
    $stmt->execute($event);
}

echo "Database klaar! Admin: admin / admin123\n";
