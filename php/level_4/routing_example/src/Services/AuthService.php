<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdminRepository;

class AuthService
{
    public function __construct(private readonly AdminRepository $admins) {}

    /**
     * Probeert in te loggen en zet bij succes de admin in de sessie.
     *
     * @return bool true als de gegevens kloppen
     */
    public function attempt(string $username, string $password): bool
    {
        $admin = $this->admins->findByUsername($username);

        // geen onderscheid tussen verkeerde naam of verkeerd wachtwoord
        if ($admin === null || !password_verify($password, $admin['password'])) {
            return false;
        }

        $_SESSION['admin'] = $admin['username'];

        return true;
    }

    /**
     * Logt de huidige admin uit.
     */
    public function logout(): void
    {
        unset($_SESSION['admin']);
    }

    /**
     * Is er op dit moment een admin ingelogd?
     */
    public function check(): bool
    {
        return !empty($_SESSION['admin']);
    }

    /**
     * Stuurt naar de loginpagina als er niemand is ingelogd.
     * Gebruik dit bovenin elke admin-actie om die af te schermen.
     */
    public function requireLogin(): void
    {
        if (!$this->check()) {
            redirect('/login');
        }
    }
}
