<?php

namespace App\Repositories\Contracts;


interface UserRepositoryInterface
{
    public function findByUser(string $name, string $email);
}