<?php

namespace App\Repositories\Eloquent;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{

    private $model;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage = 10)
    {
        $pagination = $this->model::with(['author:id,name'])->paginate($perPage);
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
        $book = $this->model::with(['author:id,name,date_birth'])->where('id', $id)->get();
        if (!$book) {
            return null;
        }

        return $book;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $book = $this->findById($id);

        if ($book) {
            $book[0]->update($data);
            return $book;
        }

        return null;
    }

    public function delete(int $id)
    {
        $book = $this->findById($id);

        if ($book) {
            $book[0]->delete();
            return true;
        }

        return false;
    }
}
