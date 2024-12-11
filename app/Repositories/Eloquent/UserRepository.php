<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByUser(string $name, string $email)
    {
        return $this->model->where('name', $name)->where('email', $email)->get();
    }

}
