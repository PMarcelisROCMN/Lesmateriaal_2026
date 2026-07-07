-- Tabel voor events, gebaseerd op de Event domain class (id, title, description)
DROP TABLE IF EXISTS events;

CREATE TABLE events (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    description TEXT NOT NULL
);

-- Wat voorbeeld-data om mee te testen
INSERT INTO events (title, description) VALUES
    ('Open dag', 'Kom langs en bekijk de opleiding.'),
    ('Hackathon', 'Bouw in een dag een eigen webapp.'),
    ('Gastles PHP', 'Een ontwikkelaar vertelt over werken met PHP.');

-- Tabel voor gebruikers, gebaseerd op de User domain class.
-- SQLite kent geen boolean, dus isAdmin is een INTEGER (0 = nee, 1 = ja).
-- De password kolom bevat een hash (password_hash), nooit het echte wachtwoord.
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    isAdmin  INTEGER NOT NULL DEFAULT 0
);

-- De voorbeeld-gebruikers worden in setup.php aangemaakt,
-- want daar kunnen we het wachtwoord netjes hashen met password_hash().
