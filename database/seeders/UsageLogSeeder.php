<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use App\Models\UsageLog;
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
        $code = DiscountCode::factory()->create(['group_id'=>DiscountCodeGroups::factory()->create()]);
        UsageLog::factory()->create(['code_id' =>$code->id ,'code' => $code->code]);
    }
}
