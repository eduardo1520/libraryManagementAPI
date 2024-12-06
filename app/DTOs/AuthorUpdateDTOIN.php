<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class AuthorUpdateDTOIN
{
    public function __construct(
          public int $id,
          public ?string $name,
          public ?Carbon $dateBirth
    ) {}
}
