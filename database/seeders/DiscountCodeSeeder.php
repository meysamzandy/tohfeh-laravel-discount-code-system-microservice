<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\DiscountCodeGroups;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DiscountCode::factory()->create(['group_id' => DiscountCodeGroups::factory()->create()]);
    }
}
