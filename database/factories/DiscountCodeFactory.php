<?php

namespace Database\Factories;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiscountCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscountCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify(),
            'created_type' => $this->faker->randomElement(['auto','manual']) ,
            'access_type' => $this->faker->randomElement(['public','private']) ,
            'usage_limit' => $this->faker->numberBetween(0,20),
            'usage_count' =>$this->faker->numberBetween(0,20) ,
            'usage_limit_per_user' =>$this->faker->numberBetween(0,10) ,
            'first_buy' => $this->faker->boolean,
            'has_market' => $this->faker->boolean ,
            'cancel_date' => $this->faker->randomElement([null, time() - 1296000 /* 15 days */,  time() - 2678400 /* 31 days */]),
        ];
    }
}
