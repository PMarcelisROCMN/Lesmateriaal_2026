<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\User;
use PDO;

/*
 * REPOSITORY
 * Enige plek in de applicatie die direct met de database praat.
 * Zet ruwe DB-rijen om naar User-objecten via hydrate().
 * Geen businesslogica, geen validatie. Alleen data ophalen en opslaan.
 */
class UserRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /**
     * Zoekt een gebruiker op naam op.
     * Geeft zowel het User-object als de wachtwoordhash terug,
     * want de service heeft de hash nodig om het wachtwoord te verifiëren.
     * Het User-object zelf bevat nooit een wachtwoord.
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        return $row
            ? ['user' => $this->hydrate($row), 'password' => $row['password']]
            : null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function insert(User $user, string $passwordHash): User
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$user->username, $passwordHash]);
        return new User((int) $this->pdo->lastInsertId(), $user->username);
    }

    // Zet een ruwe DB-rij om naar een User-object.
    private function hydrate(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            username: $row['username'],
        );
    }
}
