<?php
declare(strict_types=1);

namespace App\Domain;

// Domeinklasse: een pure datastructuur zonder SQL of logica.
// Dit object wordt door alle lagen van de applicatie doorgegeven.
class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
    ) {}
}
