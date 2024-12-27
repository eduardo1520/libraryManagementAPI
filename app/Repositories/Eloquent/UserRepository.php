<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Log\Logger;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Log\Logger as LogLogger;

class UserRepository implements UserRepositoryInterface
{

    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByUser(string $name, string $email)
    {
        $user = $this->model->where('name', $name)->where('email', $email)->get();

        if (count($user) == 0) {
            throw new Exception("User invalid.");
        }

        return $user;
    }

    public function create(array $user)
    {
        return $this->model->create($user);
    }


}
