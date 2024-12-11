<?php

namespace App\Services;

use App\DTOs\UserDTOIN;
use App\Repositories\Eloquent\UserRepository;

class UserService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUser(UserDTOIN $authorDTOIN)
    {
        return $this->repository->findByUser($authorDTOIN->name, $authorDTOIN->email);
    }

}
