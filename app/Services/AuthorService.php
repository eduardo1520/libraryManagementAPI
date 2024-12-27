<?php

namespace App\Services;

use App\DTOs\AuthorCreateDTOIN;
use App\DTOs\AuthorDTOIN;
use App\Helpers\ThrowHelper;
use App\Repositories\Eloquent\AuthorRepository;
use Exception;

class AuthorService
{
    private AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAuthors($perPage)
    {
        return $this->repository->getAll($perPage);
    }

    public function getAuthor(AuthorDTOIN $authorDTOIN)
    {
        $author = $this->repository->findById($authorDTOIN->id);

        if (count($author) === 0) {
            ThrowHelper::exception('Author not found');
        }

        return $author;
    }

    public function createAuthor(AuthorCreateDTOIN $authorCreateDTOIN)
    {
        return $this->repository->create($authorCreateDTOIN->toArray());
    }
    public function deleteAuthor(AuthorDTOIN $authorDTOIN)
    {
        $author = $this->repository->delete($authorDTOIN->id);

        if (!$author) {
            ThrowHelper::exception('Author not found');
        }

        return $author;
    }
}
