<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class LoanCreateDTOIN
{
    public function __construct(
          public int $userId,
          public int $bookId,
          public Carbon $loanDate,
          public Carbon $returnDate,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'book_id' => $this->bookId,
            'loan_date' => $this->loanDate->format('Y-m-d'),
            'return_date' => $this->returnDate->format('Y-m-d'),
        ];
    }
}
