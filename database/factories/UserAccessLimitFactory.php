<?php

namespace Database\Factories;

use App\Models\UserAccessLimit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserAccessLimitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAccessLimit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
        ];
    }
}
