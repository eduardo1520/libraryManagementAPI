<?php

namespace App\DTOs;

readonly class UserDTOIN
{
    public function __construct(
          public string $name,
          public string $email,
          public string $password
    ) {}
}
