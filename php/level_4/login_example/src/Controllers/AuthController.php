<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Domain\User;
use RuntimeException;

/*
 * CONTROLLER
 * Verwerkt HTTP-verzoeken en stuurt responses terug.
 * Valideert de invoer: zijn de velden aanwezig en correct?
 * Roept de service aan voor de businesslogica.
 * Geen SQL, geen businesslogica. Alleen verkeer dirigeren.
 */
class AuthController
{
    public function __construct(private readonly AuthService $authService) {}

    public function login(string $username, string $password): User
    {
        $error = $this->validateLogin($username, $password);
        if ($error) {
            throw new RuntimeException($error);
        }

        $user = $this->authService->login($username, $password);
        return $user;
    }

    public function register(string $username, string $password): User
    {
        $error = $this->validateRegister($username, $password);
        if ($error) {
             throw new RuntimeException($error);
        }

        $user = $this->authService->register($username, $password);
        return $user;
    }

    public function logout(): void
    {
        $this->authService->logout();
    }

    // Geeft de ingelogde gebruiker terug. Handig om de sessie te controleren.
    public function me(): User
    {
        $user = $this->authService->requireUser();
        return $user;
    }

    // Geeft een foutmelding terug als de invoer ongeldig is, anders een lege string.
    private function validateLogin(string $username, string $password): string
    {
         if ($username === '' || $password === '') {
            return 'Gebruikersnaam en wachtwoord zijn verplicht.';
        }
        return '';
    }

    private function validateRegister(string $username, string $password): string
    {
        if ($username === '' || $password === '') {
            return 'Gebruikersnaam en wachtwoord zijn verplicht.';
        }
        return '';
    }
}