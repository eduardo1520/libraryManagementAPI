<?php

namespace App\Services;

use App\DTOs\LoanCreateDTOIN;
use App\DTOs\LoanDTOIN;
use App\Repositories\Eloquent\LoanRepository;

class LoanService
{
    private LoanRepository $repository;

    public function __construct(LoanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getLoans($perPage)
    {
        return $this->repository->getAll($perPage);
    }

    public function getLoan(LoanDTOIN $LoanDTOIN)
    {
        return $this->repository->findById($LoanDTOIN->id);
    }

    public function createBook(LoanCreateDTOIN $LoanCreateDTOIN)
    {
        return $this->repository->create($LoanCreateDTOIN->toArray());
    }

    public function updateLoan(LoanDTOIN $LoanDTOIN, array $data)
    {
        return $this->repository->update($LoanDTOIN->id, $data);
    }
    public function deleteLoan(LoanDTOIN $LoanDTOIN)
    {
        return $this->repository->delete($LoanDTOIN->id);
    }
}
