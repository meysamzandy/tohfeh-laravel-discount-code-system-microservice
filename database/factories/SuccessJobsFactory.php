<?php

namespace Database\Factories;

use App\Models\SuccessJobs;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuccessJobsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuccessJobs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resultStats' => $this->faker->boolean,
            'body' => $this->faker->words(5),
            'message' => $this->faker->words(5),
            'statusCode' => 201,
        ];
    }
}
