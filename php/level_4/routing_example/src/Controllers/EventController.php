<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\EventService;

class EventController
{
    public function __construct(private readonly EventService $events) {}

    /**
     * Toont de agenda met de aankomende evenementen.
     */
    public function index(): void
    {
        view('events/index', ['events' => $this->events->upcoming()]);
    }

    /**
     * Toont de detailpagina van één evenement.
     *
     * @param string $id komt als tekst uit de URL, wordt naar int gecast
     */
    public function show(string $id): void
    {
        view('events/show', ['event' => $this->events->find((int) $id)]);
    }
}
