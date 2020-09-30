<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use App\Models\UsageLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsageLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $group_id = DiscountCodeGroups::factory()->create();
        $code = DiscountCode::factory(5)->create(['group_id' => $group_id]);

        foreach ($code as $value) {
            UsageLog::factory()->create(['code_id' => $value->id, 'code' => $value->code]);
        }

        // full usage
        $group_id = DiscountCodeGroups::factory()->create();
        $code = DiscountCode::factory()->create(['group_id' => $group_id, 'usage_limit' => 10, 'usage_count' => 5, 'usage_limit_per_user' => 5, 'first_buy' => 0,'cancel_date'=>Carbon::today()->subDays(15)]);
        for ($i = 0; $i <= 4; $i++) {
            UsageLog::factory()->create(['code_id' => $code->id, 'code' => $code->code, 'uuid' => 'ee5ec88c-811a-3cd6-8b81-5baa6624c80b']);
        }
        $usage = $code->usage_count + $i;
        DiscountCode::query()->where('id', $code->id)->update(['usage_count' => $usage]);

    }
}
