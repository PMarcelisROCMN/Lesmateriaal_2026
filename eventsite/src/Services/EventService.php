<?php

namespace App\Services;

use App\Domain\Event;
use App\Repositories\EventRepository;
use Exception;


class EventService
{
    public function __construct(private EventRepository $repository) {}

    public function getAllEvents(): array
    {
        return $this->repository->getAll();
    }

    public function getEvent(int $id): ?Event
    {
        return $this->repository->getById($id);
    }

    public function searchEvents($searchTerm){
        return $this->repository->getBySearchTerm($searchTerm);
    }


    public function addEvent(string $title, string $date, string $description, string $content): array
    {
        $errors = $this->checkValidation($title, $date, $description, $content);

        // Fouten? Dan slaan we niks op en geven we ze terug.
        if (!empty($errors)) {
            return $errors;
        }

        $this->repository->create($title, $date, $description, $content);

        return $errors;
    }

    public function editEvent(Event $event): array
    {
        $errors = $this->checkValidation($event->title, $event->date, $event->description, $event->content);

        // Fouten? Dan slaan we niks op en geven we ze terug.
        if (!empty($errors)) {
            return $errors;
        }

        $this->repository->edit($event);

        return $errors;
    }

    public function deleteEvent($id)
    {

    $event = $this->repository->getById($id);

    if(!$event)
        throw new Exception("could not find event");

        return $this->repository->delete($id);
    }

    private function checkValidation(string $title, string $date, string $description, string $content): array
    {
        $errors = [];

        if (mb_strlen($title) < 5 || mb_strlen($title) > 20) {
            $errors[] = 'De titel moet tussen de 5 en 20 tekens zijn';
        }

        if ($date === '') {
            $errors[] = 'Kies een datum';
        }

        if ($description === '') {
            $errors[] = 'Vul een korte beschrijving in';
        }

        if ($content === '') {
            $errors[] = 'Vul de volledige tekst in';
        }

        return $errors;
    }
}