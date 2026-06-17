<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Event;
use PDO;

class EventRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /**
     * Haalt alle evenementen op, oudste datum eerst.
     *
     * @return Event[]
     */
    public function all(): array
    {
        $rows = $this->pdo->query('SELECT * FROM events ORDER BY date')->fetchAll();

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->hydrate($row);
        }

        return $events;
    }

    /**
     * Haalt alleen de evenementen vanaf vandaag op.
     *
     * @return Event[]
     */
    public function upcoming(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE date >= date('now') ORDER BY date");
        $stmt->execute();

        $events = [];
        foreach ($stmt->fetchAll() as $row) {
            $events[] = $this->hydrate($row);
        }

        return $events;
    }

    /**
     * Zoekt één evenement op id.
     *
     * @return Event|null null als het niet bestaat
     */
    public function findById(int $id): ?Event
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Slaat een nieuw evenement op.
     */
    public function insert(string $title, string $description, string $location, string $date): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO events (title, description, location, date) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$title, $description, $location, $date]);
    }

    /**
     * Werkt een bestaand evenement bij.
     */
    public function update(int $id, string $title, string $description, string $location, string $date): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE events SET title = ?, description = ?, location = ?, date = ? WHERE id = ?'
        );
        $stmt->execute([$title, $description, $location, $date, $id]);
    }

    /**
     * Verwijdert een evenement.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM events WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Zet één database-rij om naar een Event-object.
     *
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Event
    {
        return new Event(
            id: (int) $row['id'],
            title: $row['title'],
            description: $row['description'],
            location: $row['location'],
            date: $row['date'],
        );
    }
}
