<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class AdminRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /**
     * Zoekt een admin op gebruikersnaam. Geeft de hele rij terug, inclusief
     * de wachtwoordhash, want die heeft de AuthService nodig om te vergelijken.
     *
     * @return array{id: int, username: string, password: string}|null
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
