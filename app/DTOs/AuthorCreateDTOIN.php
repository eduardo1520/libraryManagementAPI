<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class AuthorCreateDTOIN
{
    public function __construct(
          public string $name,
          public Carbon $dateBirth
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'date_birth' => $this->dateBirth->format('Y-m-d'),
        ];
    }
}
