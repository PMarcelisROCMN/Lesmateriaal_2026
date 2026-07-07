<?php

namespace App\Repositories;

use App\Domain\User;
use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * Zoekt een gebruiker op username.
     * Geeft een array terug met de User én de wachtwoord-hash, of null.
     * De hash houden we los van het User-object, zodat het wachtwoord
     * niet per ongeluk ergens in de app blijft rondslingeren.
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return [
            'user'     => $this->hydrate($row),
            'password' => $row['password'],
        ];
    }

    /**
     * Zoekt een gebruiker op id. Handig om na het inloggen
     * de gebruiker uit de sessie weer op te halen.
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * Maakt een nieuwe gebruiker aan. Het wachtwoord dat je meegeeft
     * is al gehasht (dat doet de AuthService).
     */
    public function create(string $username, string $hashedPassword, bool $isAdmin = false): User
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (username, password, isAdmin) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $isAdmin ? 1 : 0]);

        return new User((int) $this->pdo->lastInsertId(), $username, $isAdmin);
    }

    private function hydrate(array $row): User
    {
        return new User(
            (int) $row['id'],
            $row['username'],
            (bool) $row['isAdmin'],
        );
    }
}
