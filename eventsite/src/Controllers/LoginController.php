<?php

namespace App\Controllers;

use App\Services\AuthService;

class LoginController
{
    public function __construct(private AuthService $authService) {}

    private function redirect(string $path): void
    {
        // Onder de ingebouwde PHP-server draait de app op de root,
        // onder Apache in de submap. Zet de juiste basis voor de redirect.
        $base = php_sapi_name() === 'cli-server' ? '' : '/lesmateriaal/eventsite';
        header('Location: ' . $base . $path);
        exit;
    }

    public function showLogin(array $errors = []): void
    {
        $currentUser = $this->authService->currentUser();
        require __DIR__ . '/../Views/login_page.php';
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Input-validatie: zijn de velden ingevuld?
        $errors = [];
        if ($username === '' || $password === '') {
            $errors[] = 'Vul een gebruikersnaam en wachtwoord in';
        }

        if (!empty($errors)) {
            $this->showLogin($errors);
            return;
        }

        // De service checkt of de combinatie klopt.
        $errors = $this->authService->login($username, $password);

        if (!empty($errors)) {
            $this->showLogin($errors);
            return;
        }

        $this->redirect('/events');
    }

    public function showRegister(array $errors = []): void
    {
        $currentUser = $this->authService->currentUser();
        require __DIR__ . '/../Views/register_page.php';
    }

    public function register(): void
    {
        $username        = $_POST['username'] ?? '';
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        // Input-validatie: de vorm van het formulier.
        $errors = [];
        if ($username === '' || $password === '' || $confirmPassword === '') {
            $errors[] = 'Vul alle velden in';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'De wachtwoorden zijn niet gelijk';
        }

        // Fout in het formulier? Meteen terug, we gaan niet eens naar de service.
        if (!empty($errors)) {
            $this->showRegister($errors);
            return;
        }

        // De service checkt de business-regels (naam vrij, wachtwoord lang genoeg).
        $errors = $this->authService->register($username, $password);

        if (!empty($errors)) {
            $this->showRegister($errors);
            return;
        }

        $this->redirect('/events');
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }
}
