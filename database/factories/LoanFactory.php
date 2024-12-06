<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $loanDate = $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');
        return [
            'user_id'     => User::inRandomOrder()->first()->id,
            'book_id'     => Book::inRandomOrder()->first()->id,
            'loan_date'   => $loanDate,
            'return_date' => $this->faker->dateTimeBetween($loanDate, 'now')->format('Y-m-d'),
        ];
    }
}
