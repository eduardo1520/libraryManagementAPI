<?php

namespace App\DTOs;

use Carbon\Carbon;

readonly class LoanDTOOUT
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $book_id,
        public Carbon $loan_date,
        public Carbon $return_date,
        public BookDTOOUT $book,
        public UserDTOOUT $user
    ) {}

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'loan_date'   => Carbon::parse($this->loan_date)->format('d-m-Y'),
            'return_date' => Carbon::parse($this->return_date)->format('d-m-Y'),
            'book'        => $this->book->getBook(),
            'user'        => $this->user->toArray()
        ];
    }
}
