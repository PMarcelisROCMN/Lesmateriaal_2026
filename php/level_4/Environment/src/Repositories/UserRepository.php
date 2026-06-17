<?php

namespace App\Repositories;

use PDO;
use App\Domain\User;

class UserRepository {

    public function __construct(
       private readonly PDO $pdo
    ){}

    public function findByUsername(string $username) : ?User{ 
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch();

        if ($result === false) {
            return null;
        }

        return $this->hydrate($result);
    }

    private function hydrate(array $row) : User {
        $user = new User(
            $row['id'], 
            $row['username'],
            $row['email']
            );
        return $user;
    }
}