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
        // Onder de ingebouwde PHP-server draait de app op de root,
        // onder Apache in de submap. Zet de juiste basis voor de redirect.
        $base = php_sapi_name() === 'cli-server' ? '' : '/lesmateriaal/eventsite';
        header('Location: ' . $base . $path);
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

        // wie is er ingelogd? (null als er niemand is ingelogd)
        $currentUser = $this->authService->currentUser();

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

        // voor de nav bovenaan: wie is er ingelogd?
        $currentUser = $this->authService->currentUser();

        require __DIR__ . '/../Views/event_details.php';
    }

    public function showEventCreationForm(array $errors = [])
    {
        // requireAdmin geeft de ingelogde beheerder terug, handig voor de nav.
        $currentUser = $this->authService->requireAdmin();
        require __DIR__ . '/../Views/event_create.php';
    }

    public function create()
    {
        $this->authService->requireAdmin();

        $title = $_POST['title'] ?? '';
        $date = $_POST['date'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';

        // De service controleert de invoer en geeft eventuele fouten terug.
        $errors = $this->eventService->addEvent($title, $date, $description, $content);

        if (!empty($errors)) {
            $this->showEventCreationForm($errors);
            return;
        }

        $this->redirect('/events');
    }

    public function delete(int $eventId){
        $this->authService->requireAdmin();

        $this->eventService->deleteEvent($eventId);

        $this->redirect('/events');
    }

    public function showEdit(int $eventId, array $errors = [])
    {
        $currentUser = $this->authService->requireAdmin();
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
        $date = $_POST['date'] ?? '';
        $description = $_POST['description'] ?? '';
        $content = $_POST['content'] ?? '';

        $changedEvent = new Event($eventId, $title, $date, $description, $content);

        // De service controleert en geeft eventuele fouten terug.
        $errors = $this->eventService->editEvent($changedEvent);

        if (!empty($errors)) {
            $this->showEdit($eventId, $errors);
            return;
        }

        $this->redirect('/events');
    }
}
