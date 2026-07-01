<?php

namespace App\Services;
use App\Domain\User;
use PDO;
use Exception;

class AuthService
{

    private User $fakeUser;

    // not using pdo at the moment
    public function __construct(private PDO $pdo)
    {
    }


    public function login(string $username, $password): User
    {

        $_SESSION['user_id'] = $this->fakeUser->id;

        return $this->fakeUser;
    }

    public function currentUser(): ?User
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return $this->fakeUser;
    }

    public function requireUser(): User
    {
        $user = $this->currentUser();
        if ($user === null) {
            throw new Exception('Niet ingelogd');
        }
        return $user;
    }

    public function requireAdmin() : User {
        $user = $this->requireUser();
        if (!$user->isAdmin)
            throw new Exception("User is not an admin");
        
        return $user;
    }
}
