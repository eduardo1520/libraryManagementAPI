<?php

namespace App\DTOs;

readonly class BookDTOOUT
{
    public function __construct(
        public int $id,
        public string $title,
        public int $year_published,
        public AuthorDTOOUT $author
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year_published' => $this->year_published,
            'author' => $this->author->getAuthor()
        ];
    }
}
