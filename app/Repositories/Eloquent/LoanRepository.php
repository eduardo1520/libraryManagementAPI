<?php

namespace App\Repositories\Eloquent;

use App\Models\Loan;
use App\Repositories\Contracts\LoanRepositoryInterface;

class LoanRepository implements LoanRepositoryInterface
{

    private $model;

    public function __construct(Loan $model)
    {
        $this->model = $model;
    }

    public function getAll($perPage = 10)
    {
        $pagination = $this->model::with('user:id,name', 'book:id,title')->paginate($perPage);
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
        $loan = $this->model::with(['user:id,name', 'book:id,title,author_id,year_published'])->where('id', $id)->get();
        if (!$loan) {
            return null;
        }

        return $loan;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $loan = $this->findById($id);

        if ($loan) {
            $loan[0]->update($data);
            return $loan;
        }

        return null;
    }

    public function delete(int $id)
    {
        $loan = $this->findById($id);

        if ($loan) {
            $loan[0]->delete();
            return true;
        }

        return false;
    }
}
