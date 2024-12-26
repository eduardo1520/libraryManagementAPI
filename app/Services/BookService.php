<?php

namespace App\Services;

use App\DTOs\BookCreateDTOIN;
use App\DTOs\BookDTOIN;
use App\Helpers\ThrowHelper;
use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\BookRepository;
use Exception;

class BookService
{
    private BookRepository $repository;
    private AuthorRepository $authorRepository;

    public function __construct(BookRepository $repository, AuthorRepository $authorRepository)
    {
        $this->repository = $repository;
        $this->authorRepository = $authorRepository;
    }

    public function getBooks($perPage)
    {
        return $this->repository->getAll($perPage);
    }

    public function getBook(BookDTOIN $bookDTOIN)
    {
        $book = $this->repository->findById($bookDTOIN->id);

        if (count($book) == 0) {
            ThrowHelper::exception('Book not found');
        }

        return $book;
    }

    public function createBook(BookCreateDTOIN $bookCreateDTOIN)
    {
        return $this->repository->create($bookCreateDTOIN->toArray());
    }

    public function updateBook(BookDTOIN $bookDTOIN, array $data)
    {
        if (empty($bookDTOIN->id)) {
            ThrowHelper::exception('Book not found');
        }

        return $this->repository->update($bookDTOIN->id, $data);
    }
    public function deleteBook(BookDTOIN $bookDTOIN)
    {
        if (empty($bookDTOIN->id)) {
            ThrowHelper::exception('Book not found');
        }

        return $this->repository->delete($bookDTOIN->id);
    }
}
