<?php

namespace App\Repositories\Eloquent;

use App\Models\Author;
use App\Repositories\Contracts\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{

    private $model;

    public function __construct(Author $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage = 10)
    {
        $pagination = $this->model->paginate($perPage);
        return [
            'data' => $pagination->items(),
            'pagination' => [
                'total' => $pagination->total(),
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
            ],
        ];
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $author = $this->findById($id);

        if ($author) {
            $author->update($data);
            return $author;
        }

        return null;
    }

    public function delete(int $id)
    {
        $author = $this->findById($id);

        if ($author) {
            $author->delete();
            return true;
        }

        return false;
    }
}
