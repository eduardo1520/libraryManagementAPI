<?php

namespace App\DTOs;

use App\Helpers\PasswordHelper as HelpersPasswordHelper;

readonly class UserCreateDTOIN
{
    public function __construct(
          public string $name,
          public string $email,
          public string $password
    ) {}

  public function toArray(): array
  {
      return [
          'name' => $this->name,
          'email' => $this->email,
          'password' => HelpersPasswordHelper::hash($this->password),
      ];
  }
}
