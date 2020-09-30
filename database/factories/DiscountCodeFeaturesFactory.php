<?php

namespace Database\Factories;

use App\Models\DiscountCodeFeatures;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiscountCodeFeaturesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscountCodeFeatures::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $code_type = $this->faker->randomElement(['free', 'percent', 'price']);
        return [
            'plan_id' => $this->faker->numerify(),
            'start_time' => now(),
            'end_time' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+5 days'),
            'code_type' => $code_type,
            'percent' => $code_type === 'percent' ? $this->faker->numberBetween(1, 100) : null,
            'limit_percent_price' => $code_type === 'percent' ? $this->faker->randomElement([null, $this->faker->numberBetween(1, 1000)]) : null,
            'price' => $code_type === 'price' ? $this->faker->numberBetween(1000, 10000) : null,
            'description' => $this->faker->sentence,
        ];

    }
}
