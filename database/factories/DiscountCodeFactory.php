<?php

namespace Database\Factories;

use App\Http\Helper\SmallHelper;
use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use Carbon\Carbon;
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
        $prefix = 'test_' ;
        $stringType = config('settings.generatorString.bothCharacter') ;
        $length = config('settings.automateCodeLength') ;
        return [
            'code' => SmallHelper::codeGenerator($prefix, $stringType, $length),
            'created_type' => $this->faker->randomElement(['auto','manual']) ,
            'access_type' => $this->faker->randomElement(['public','private']) ,
            'usage_limit' => $this->faker->numberBetween(0,20),
            'usage_count' =>$this->faker->numberBetween(0,20) ,
            'usage_limit_per_user' =>$this->faker->numberBetween(0,10) ,
            'first_buy' => $this->faker->boolean,
            'has_market' => $this->faker->boolean ,
            'cancel_date' => $this->faker->randomElement([null, Carbon::today()->subDays(15) /* 15 days */,  Carbon::today()->subDays(31) /* 31 days */]),
        ];
    }
}
