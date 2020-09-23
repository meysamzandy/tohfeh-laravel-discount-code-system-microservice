<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use App\Models\MarketAccessLimit;
use App\Models\UsageLog;
use App\Models\UserAccessLimit;
use Illuminate\Database\Seeder;

class FullSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $group1 = DiscountCodeGroups::factory()->create();
        $code1 = DiscountCode::factory(5)->create([
            'group_id' => $group1,
            'created_type' => 'auto',
            'access_type' => 'public',
            'usage_limit' => 50,
            'usage_count' => 0,
            'usage_limit_per_user' => 10,
            'first_buy' => 0,
            'has_market' => 0,
            'cancel_date' => null

        ]);
        DiscountCodeFeatures::factory(5)->create(['group_id' => $group1]);
//        MarketAccessLimit::factory()->create(['code_id' => $code ]);
//        UserAccessLimit::factory()->create(['code_id' => $code ]);
//        UsageLog::factory()->create(['code_id' =>$code->id ,'code' => $code->code]);
    }
}
