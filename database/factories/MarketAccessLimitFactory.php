<?php

namespace Database\Factories;

use App\Models\MarketAccessLimit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MarketAccessLimitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarketAccessLimit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['mobileApp', 'tvApp', 'iosApp', 'spa', 'cafebazzar', 'myket']),
            'version_major' => $this->faker->numberBetween(1, 999),
            'version_minor' => $this->faker->numberBetween(1, 999),
            'version_patch' => $this->faker->numberBetween(1, 999),
        ];
    }
}
