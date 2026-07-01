<?php

namespace App\Services;

use App\Domain\Event;
use App\Repositories\EventRepository;
use Exception;
use InvalidArgumentException;


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


    public function addEvent(string $title, string $description): Event
    {
        $errors = $this->checkValidation($title, $description);

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        return $this->repository->create($title, $description);
    }

    public function editEvent(Event $event) : Event
    {
          $errors = $this->checkValidation($event->title, $event->description);

        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }
        return $this->repository->edit($event);
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
            $errors[] = "title cannot be shorter than 5- or longer than 20 characters";
        }

        return $errors;
    }
}