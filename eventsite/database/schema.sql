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
