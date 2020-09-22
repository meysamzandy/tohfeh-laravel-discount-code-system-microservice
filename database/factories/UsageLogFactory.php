<?php

namespace Database\Factories;

use App\Models\UsageLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsageLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UsageLog::class;

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
