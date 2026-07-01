<?php

namespace App\Controllers;

use App\Domain\Event;
use App\Services\EventService;
use App\Services\AuthService;

class EventController
{
    public function __construct(private EventService $eventService, private AuthService $authService) {}

    private function redirect(string $path): void
    {
        header('Location: /lesmateriaal/eventsite' . $path);
        exit;
    }

    public function index(): void
    {

        // lees de GET uit voor een zoekbalk type shi
        $searchTerm = $_GET['q'] ?? null;

        if ($searchTerm) {
            $events = $this->eventService->searchEvents($searchTerm);
        } else {
            $events = $this->eventService->getAllEvents();
        }
        
        require  __DIR__ . '/../Views/events_overview.php';
    }

    public function show(int $id): void
    {
        // $this->authService->requireUser();

        $event = $this->eventService->getEvent($id);

        if ($event === null) {
            http_response_code(404);
            echo 'Event niet gevonden';
            return;
        }

        require __DIR__ . '/../Views/event_details.php';
    }

    public function showEventCreationForm()
    {
        $this->authService->requireAdmin();
        require __DIR__ . '/../Views/event_create.php';
    }

    public function create()
    {
        $this->authService->requireAdmin();

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        // Validatie: zijn de velden gevuld?
        if ($title === '' || $description === '') {
            // Toon het overzicht opnieuw, mét een foutmelding.
            $error = 'Vul zowel een titel als een beschrijving in.';
            $this->showEventCreationForm();
            return;
        }

        $this->eventService->addEvent($title, $description);

        $this->redirect('/events');
    }

    public function showEdit(int $eventId)
    {
        $this->authService->requireAdmin();
        $event = $this->eventService->getEvent($eventId);
        require __DIR__ . '/../Views/event_edit.php';
    }

    public function edit(int $eventId)
    {
        $this->authService->requireAdmin();


        $event = $this->eventService->getEvent($eventId);

        if (!$event) {
            $this->redirect('/events');
        }

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        if ($title === '' || $description === '') {
            $error = 'Vul zowel een titel als een beschrijving in.';
            $this->showEdit($eventId);
            return;
        }

        $changedEvent = new Event($eventId, $title, $description);

        $this->eventService->editEvent($changedEvent);

        $this->redirect('/events');
    }

    public function deleteEvent(int $eventId)
    {
        $this->authService->requireAdmin();
        $this->eventService->deleteEvent($eventId);
    }
}
