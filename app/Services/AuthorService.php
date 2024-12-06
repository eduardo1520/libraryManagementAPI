<?php

namespace App\Services;

use App\DTOs\AuthorCreateDTOIN;
use App\DTOs\AuthorDTOIN;
use App\Repositories\Eloquent\AuthorRepository;

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
        return $this->repository->findById($authorDTOIN->id);
    }

    public function createAuthor(AuthorCreateDTOIN $authorCreateDTOIN)
    {
        return $this->repository->create($authorCreateDTOIN->toArray());
    }
    public function deleteAuthor(AuthorDTOIN $authorDTOIN)
    {
        return $this->repository->delete($authorDTOIN->id);
    }
}
