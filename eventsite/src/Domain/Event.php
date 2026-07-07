<?php

namespace App\Domain;

class Event {
    public function __construct(
    public readonly int $id,
    public readonly string $title,
    public readonly string $date,
    public readonly string $description,
    public readonly string $content,
    ){}
}