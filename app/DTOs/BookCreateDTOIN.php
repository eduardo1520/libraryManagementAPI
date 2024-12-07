<?php

namespace App\DTOs;

readonly class BookCreateDTOIN
{
    public function __construct(
          public string $title,
          public int $yearPublished,
          public int $authorId
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'author_id' => $this->authorId,
            'year_published' => $this->yearPublished,
        ];
    }
}
