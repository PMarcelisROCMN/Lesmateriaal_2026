<?php

namespace App\Domain;

class User {

    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email
    ){}
}