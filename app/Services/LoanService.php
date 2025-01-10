<?php

namespace App\Services;

use App\DTOs\LoanCreateDTOIN;
use App\DTOs\LoanDTOIN;
use App\DTOs\LoanUpdateDTOIN;
use App\Helpers\ThrowHelper;
use App\Jobs\SendLoanEmail;
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

    public function createLoan(LoanCreateDTOIN $LoanCreateDTOIN)
    {
        if (empty($LoanCreateDTOIN->userId) || empty($LoanCreateDTOIN->bookId)) {
            ThrowHelper::exception('Create failed loan.');
        }

        $loan = $this->repository->create($LoanCreateDTOIN->toArray());

        $user = $loan->user;

        $loanDetails = $LoanCreateDTOIN->toArray();

        dispatch(new SendLoanEmail($user, $loanDetails));

        return $loan;
    }

    public function updateLoan(LoanUpdateDTOIN $loanUpdateDTOIN, array $data)
    {
        return $this->repository->update($loanUpdateDTOIN->id, $data);
    }
    public function deleteLoan(LoanDTOIN $LoanDTOIN)
    {
        return $this->repository->delete($LoanDTOIN->id);
    }
}
