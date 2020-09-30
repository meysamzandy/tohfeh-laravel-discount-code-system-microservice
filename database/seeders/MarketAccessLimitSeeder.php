<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use App\Models\MarketAccessLimit;
use Illuminate\Database\Seeder;

class MarketAccessLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        MarketAccessLimit::factory()->create(['code_id' => DiscountCode::factory()->create(['group_id'=>DiscountCodeGroups::factory()->create()])]);
    }
}
