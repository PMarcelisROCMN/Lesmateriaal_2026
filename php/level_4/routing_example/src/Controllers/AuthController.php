<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    public function __construct(private readonly AuthService $auth) {}

    /**
     * Toont het loginformulier.
     */
    public function showLogin(): void
    {
        view('auth/login', ['error' => null]);
    }

    /**
     * Verwerkt het loginformulier. Bij juiste gegevens door naar het beheer,
     * anders opnieuw het formulier met een foutmelding.
     */
    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($this->auth->attempt($username, $password)) {
            redirect('/admin/events');
        }

        view('auth/login', ['error' => 'Onjuiste gebruikersnaam of wachtwoord.']);
    }

    /**
     * Logt uit en stuurt terug naar de agenda.
     */
    public function logout(): void
    {
        $this->auth->logout();
        redirect('/');
    }
}
