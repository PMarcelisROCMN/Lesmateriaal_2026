-- Referentie van het databaseschema (SQLite).
-- Je hoeft dit niet handmatig uit te voeren: Migrator.php maakt deze tabellen
-- automatisch aan bij de eerste keer dat je het project opent.

CREATE TABLE IF NOT EXISTS admins (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS events (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    description TEXT NOT NULL DEFAULT '',
    location    TEXT NOT NULL,
    date        TEXT NOT NULL
);
