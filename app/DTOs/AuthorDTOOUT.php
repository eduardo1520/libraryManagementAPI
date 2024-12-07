<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class AuthorDTOOUT
{
    public function __construct(
        public int $id,
        public string $name,
        public Carbon $dateBirth
    ) {}

    public function getFormattedDate(): string
    {
        return $this->dateBirth->format('Y-m-d');
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'date_birth' => $this->dateBirth,
        ];
    }

    public function getAuthor(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
