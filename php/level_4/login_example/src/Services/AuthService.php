<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\User;
use App\Repositories\UserRepository;
use RuntimeException;

/*
 * SERVICE
 * Bevat de businesslogica: de regels van de applicatie.
 * Geen SQL (dat is de repository), geen HTTP (dat is de controller).
 * Stelt vragen als: mag dit? Klopt dit?
 */
class AuthService
{
    public function __construct(private readonly UserRepository $repository) {}

    public function login(string $username, string $password): User
    {
        $result = $this->repository->findByUsername($username);

        if ($result === null) {
            // Nooit zeggen welk veld fout is, dat geeft aanvallers te veel informatie.
            throw new RuntimeException('Gebruikersnaam of wachtwoord onjuist.', 401);
        }

        if (!password_verify($password, $result['password'])) {
            throw new RuntimeException('Gebruikersnaam of wachtwoord onjuist.', 401);
        }

        $user = $result['user'];
        $_SESSION['user_id'] = $user->id;
        return $user;
    }

    public function register(string $username, string $password): User
    {
        if (strlen($password) < 8) {
            throw new RuntimeException('Wachtwoord moet minimaal 8 tekens bevatten.');
        }

        if ($this->repository->findByUsername($username) !== null) {
            throw new RuntimeException('Gebruikersnaam is al in gebruik.', 409);
        }

        // Wachtwoord hashen is een businessregel: het hoort niet in de controller
        // (die doet validatie) of de repository (die doet opslaan).
        $hash = password_hash($password, PASSWORD_DEFAULT);

        return $this->repository->insert(new User(0, $username), $hash);
    }

    public function logout(): void
    {
        session_destroy();
    }

    /**
     * Geeft de ingelogde gebruiker terug.
     * Gooit een 401 als niemand is ingelogd — gebruik dit voor beveiligde routes.
     */
    public function requireUser(): User
    {
        return $this->currentUser() ?? throw new RuntimeException('Niet ingelogd.', 401);
    }

    private function currentUser(): ?User
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        return $this->repository->findById((int) $_SESSION['user_id']);
    }
}
