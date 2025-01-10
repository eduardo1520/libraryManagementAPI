<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class LoanUpdateDTOIN
{
    public function __construct(
          public int $id,
          public ?int $userId,
          public ?int $bookId,
          public ?Carbon $loanDate,
          public ?Carbon $returnDate
    ) {}
}
