<?php

namespace App\Repositories;

use App\Domain\Event;
use PDO;

class EventRepository
{
    public function __construct(private PDO $pdo) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, title, date, description, content FROM events');

        $events = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $events[] = $this->hydrate($row);
        }

        return $events;
    }


    public function getById(int $id): ?Event
    {
        $stmt = $this->pdo->prepare('SELECT id, title, date, description, content FROM events WHERE id = ?');
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function create(string $title, string $date, string $description, string $content): Event
    {
        $stmt = $this->pdo->prepare('INSERT INTO events (title, date, description, content) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $date, $description, $content]);

        return new Event((int) $this->pdo->lastInsertId(), $title, $date, $description, $content);
    }

    public function edit(Event $event) : Event
    {
        $stmt = $this->pdo->prepare('UPDATE events set title = ?, date = ?, description = ?, content = ? WHERE id = ?');
        $stmt->execute([$event->title, $event->date, $event->description, $event->content, $event->id]);

        return $event;
    }

    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    public function getBySearchTerm(string $searchTerm) :?array{
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE title LIKE ?');
        $stmt->execute(['%' . $searchTerm . '%']);

        $events = $stmt->fetchAll();

        return $events ? array_map($this->hydrate(...), $events) : null;
    }

    private function hydrate(array $row): Event
    {
        return new Event(
            (int) $row['id'],
            $row['title'],
            $row['date'],
            $row['description'],
            $row['content'],
        );
    }
}
