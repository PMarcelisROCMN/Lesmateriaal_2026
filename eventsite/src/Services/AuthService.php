<?php

namespace App\Services;

use App\Domain\User;
use App\Repositories\UserRepository;
use Exception;

class AuthService
{
    public function __construct(private UserRepository $userRepo) {}

    /**
     * Probeert in te loggen. Klopt de combinatie? Dan onthouden we
     * de gebruiker in de sessie. We geven een lijst met foutmeldingen
     * terug: is die leeg, dan is het inloggen gelukt.
     */
    public function login(string $username, string $password): array
    {
        $result = $this->userRepo->findByUsername($username);

        // Bestaat de gebruiker niet, of klopt het wachtwoord niet?
        if ($result === null || !password_verify($password, $result['password'])) {
            return ['Verkeerde gebruikersnaam of wachtwoord'];
        }

        $user = $result['user'];
        $_SESSION['user_id'] = $user->id;

        return [];
    }

    /**
     * Maakt een nieuwe gebruiker aan en logt die meteen in.
     * Geeft een lijst met foutmeldingen terug: is die leeg, dan is
     * het registreren gelukt.
     */
    public function register(string $username, string $password): array
    {
        $errors = [];

        // Bestaat deze gebruikersnaam al?
        if ($this->userRepo->findByUsername($username) !== null) {
            $errors[] = 'Die gebruikersnaam bestaat al';
        }

        // Business-regel: het wachtwoord moet lang genoeg zijn.
        if (mb_strlen($password) < 8) {
            $errors[] = 'Het wachtwoord moet minstens 8 tekens zijn';
        }

        // Iets mis? Dan maken we niks aan en geven we de fouten terug.
        if (!empty($errors)) {
            return $errors;
        }

        // Wachtwoord hashen, zodat we het nooit leesbaar opslaan.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $user = $this->userRepo->create($username, $hash);

        $_SESSION['user_id'] = $user->id;

        return $errors;
    }

    public function logout(): void
    {
        // De sessie leegmaken zodat de gebruiker weer uitgelogd is.
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Geeft de ingelogde gebruiker terug, of null als er niemand is ingelogd.
     */
    public function currentUser(): ?User
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return $this->userRepo->findById((int) $_SESSION['user_id']);
    }

    public function requireUser(): User
    {
        $user = $this->currentUser();
        if ($user === null) {
            throw new Exception('Niet ingelogd');
        }
        return $user;
    }

    public function requireAdmin(): User
    {
        $user = $this->requireUser();
        if (!$user->isAdmin) {
            throw new Exception('Je bent geen beheerder');
        }
        return $user;
    }
}