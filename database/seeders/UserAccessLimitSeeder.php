<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use App\Models\UserAccessLimit;
use Illuminate\Database\Seeder;

class UserAccessLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        UserAccessLimit::factory()->create(['code_id' => DiscountCode::factory()->create(['group_id'=>DiscountCodeGroups::factory()->create()])]);
    }
}
