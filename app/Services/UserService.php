<?php

namespace App\Services;

use App\DTOs\UserCreateDTOIN;
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

    public function createUser(UserCreateDTOIN $userCreateDTOIN)
    {
        return $this->repository->create($userCreateDTOIN->toArray());
    }

}
