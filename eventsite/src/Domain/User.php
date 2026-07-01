<?php

namespace App\Domain;

class User {

public function __construct(
    public int $id, 
    public string $username, 
    public bool $isAdmin){}
}