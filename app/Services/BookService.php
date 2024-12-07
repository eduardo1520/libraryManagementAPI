<?php

namespace App\Services;

use App\DTOs\BookCreateDTOIN;
use App\DTOs\BookDTOIN;
use App\Repositories\Eloquent\BookRepository;

class BookService
{
    private BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getBooks($perPage)
    {
        return $this->repository->getAll($perPage);
    }

    public function getBook(BookDTOIN $BookDTOIN)
    {
        return $this->repository->findById($BookDTOIN->id);
    }

    public function createBook(BookCreateDTOIN $BookCreateDTOIN)
    {
        return $this->repository->create($BookCreateDTOIN->toArray());
    }

    public function updateBook(BookDTOIN $BookDTOIN, array $data)
    {
        return $this->repository->update($BookDTOIN->id, $data);
    }
    public function deleteBook(BookDTOIN $BookDTOIN)
    {
        return $this->repository->delete($BookDTOIN->id);
    }
}
