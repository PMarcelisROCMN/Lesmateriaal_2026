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


    public function addEvent(string $title, string $description): array
    {
        $errors = $this->checkValidation($title, $description);

        // Fouten? Dan slaan we niks op en geven we ze terug.
        if (!empty($errors)) {
            return $errors;
        }

        $this->repository->create($title, $description);

        return $errors;
    }

    public function editEvent(Event $event): array
    {
        $errors = $this->checkValidation($event->title, $event->description);

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

    private function checkValidation(string $title, string $description): array
    {
        $errors = [];

        if (mb_strlen($title) < 5 || mb_strlen($title) > 20) {
            $errors[] = 'De titel moet tussen de 5 en 20 tekens zijn';
        }

        if ($description === '') {
            $errors[] = 'Vul een beschrijving in';
        }

        return $errors;
    }
}