<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'date_birth' => $this->faker->dateTimeBetween('-80 years', '-5 years')->format('Y-m-d'),
            'created_at' => Carbon::now()
        ];
    }
}
