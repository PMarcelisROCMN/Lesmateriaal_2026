<?php
declare(strict_types=1);

namespace App\Domain;

class Event
{
    // waardeobject: één evenement zoals het in de database staat
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $location,
        public readonly string $date,
    ) {}
}
