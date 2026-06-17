<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\EventService;
use RuntimeException;

class AdminEventController
{
    public function __construct(
        private readonly EventService $events,
        private readonly AuthService $auth,
    ) {}

    /**
     * Toont het beheeroverzicht met alle evenementen.
     */
    public function index(): void
    {
        $this->auth->requireLogin();

        view('admin/index', ['events' => $this->events->all()]);
    }

    /**
     * Toont een leeg formulier voor een nieuw evenement.
     */
    public function create(): void
    {
        $this->auth->requireLogin();

        view('admin/form', [
            'heading' => 'Nieuw evenement',
            'action'  => url('/admin/events'),
            'error'   => null,
            'values'  => $this->emptyValues(),
        ]);
    }

    /**
     * Slaat een nieuw evenement op, of toont het formulier opnieuw bij een fout.
     */
    public function store(): void
    {
        $this->auth->requireLogin();

        try {
            $this->events->create($_POST);
        } catch (RuntimeException $e) {
            view('admin/form', [
                'heading' => 'Nieuw evenement',
                'action'  => url('/admin/events'),
                'error'   => $e->getMessage(),
                'values'  => $this->valuesFrom($_POST),
            ]);
            return;
        }

        redirect('/admin/events');
    }

    /**
     * Toont het formulier met de huidige gegevens van een evenement.
     *
     * @param string $id komt als tekst uit de URL
     */
    public function edit(string $id): void
    {
        $this->auth->requireLogin();

        $event = $this->events->find((int) $id);

        view('admin/form', [
            'heading' => 'Evenement wijzigen',
            'action'  => url('/admin/events/' . $event->id),
            'error'   => null,
            'values'  => [
                'title'       => $event->title,
                'location'    => $event->location,
                'date'        => $event->date,
                'description' => $event->description,
            ],
        ]);
    }

    /**
     * Slaat een wijziging op, of toont het formulier opnieuw bij een fout.
     *
     * @param string $id komt als tekst uit de URL
     */
    public function update(string $id): void
    {
        $this->auth->requireLogin();

        try {
            $this->events->update((int) $id, $_POST);
        } catch (RuntimeException $e) {
            view('admin/form', [
                'heading' => 'Evenement wijzigen',
                'action'  => url('/admin/events/' . $id),
                'error'   => $e->getMessage(),
                'values'  => $this->valuesFrom($_POST),
            ]);
            return;
        }

        redirect('/admin/events');
    }

    /**
     * Verwijdert een evenement.
     *
     * @param string $id komt als tekst uit de URL
     */
    public function destroy(string $id): void
    {
        $this->auth->requireLogin();

        $this->events->delete((int) $id);

        redirect('/admin/events');
    }

    /**
     * Lege formulierwaardes (voor een nieuw evenement).
     *
     * @return array<string, string>
     */
    private function emptyValues(): array
    {
        return ['title' => '', 'location' => '', 'date' => '', 'description' => ''];
    }

    /**
     * Haalt de formulierwaardes uit de invoer, zodat we ze na een fout
     * weer in het formulier kunnen tonen.
     *
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    private function valuesFrom(array $input): array
    {
        return [
            'title'       => $input['title'] ?? '',
            'location'    => $input['location'] ?? '',
            'date'        => $input['date'] ?? '',
            'description' => $input['description'] ?? '',
        ];
    }
}
