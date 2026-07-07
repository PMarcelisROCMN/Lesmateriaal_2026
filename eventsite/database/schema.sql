-- Tabel voor events, gebaseerd op de Event domain class.
-- description = korte samenvatting (op het overzicht en de detailpagina)
-- content     = de volledige tekst (alleen op de detailpagina)
DROP TABLE IF EXISTS events;

CREATE TABLE events (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    date        TEXT NOT NULL,
    description TEXT NOT NULL,
    content     TEXT NOT NULL
);

-- Wat voorbeeld-data om mee te testen
INSERT INTO events (title, date, description, content) VALUES
    ('Open dag', '2026-09-20', 'Kom langs en ontdek de opleiding Software Development.',
     'Tijdens de open dag laten studenten en docenten zien waar je bij Software Development mee bezig bent. Je loopt mee met een echte les, bekijkt projecten van studenten en kunt al je vragen stellen over de opleiding, de stages en je toekomstige baan.

Je bent van harte welkom, aanmelden is niet nodig.'),

    ('Hackathon', '2026-10-11', 'Bouw in een dag met je team een werkende webapp.',
     'In teams van drie tot vier personen bouw je in een dag een werkende webapplicatie rond een thema dat we die ochtend bekendmaken. Docenten en developers uit het werkveld lopen rond om je te helpen als je vastloopt.

Aan het eind pitcht elk team zijn app en kiest de jury een winnaar. Neem je eigen laptop mee, eten en drinken worden verzorgd.'),

    ('Gastles PHP', '2026-11-05', 'Een developer uit het werkveld vertelt over werken met PHP.',
     'Een ervaren backend-developer neemt je mee in hoe PHP er in de praktijk uitziet: van een simpel script tot een echte applicatie met een framework, een database en tests. Je krijgt een eerlijk beeld van het werk en genoeg tijd om vragen te stellen.'),

    ('Excursie webbureau', '2026-11-27', 'Kijk een dag mee achter de schermen bij een echt webbureau.',
     'We gaan op bezoek bij een lokaal webbureau. Je ziet hoe een team werkt aan opdrachten voor klanten, hoe een project van idee tot oplevering loopt en welke rollen daar allemaal bij komen kijken. Een mooie kans om te proeven aan het werkveld.');

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
