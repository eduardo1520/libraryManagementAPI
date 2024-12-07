<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class LoanCreateDTOIN
{
    public function __construct(
          public int $user_id,
          public int $book_id,
          public Carbon $loan_date,
          public Carbon $return_date,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'book_id' => $this->book_id,
            'loan_date' => $this->loan_date->format('Y-m-d'),
            'return_date' => $this->return_date->format('Y-m-d'),
        ];
    }
}
