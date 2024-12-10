<?php

namespace App\Services;

use App\DTOs\LoanCreateDTOIN;
use App\DTOs\LoanDTOIN;
use App\Jobs\SendLoanEmail;
use App\Repositories\Eloquent\LoanRepository;
use Illuminate\Support\Facades\Log;

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

    public function createLoan(LoanCreateDTOIN $LoanCreateDTOIN)
    {
        $loan = $this->repository->create($LoanCreateDTOIN->toArray());

        if (!$loan) {
            return false;
        }

        $user = $loan->user;

        $loanDetails = $LoanCreateDTOIN->toArray();

        dispatch(new SendLoanEmail($user, $loanDetails));
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
