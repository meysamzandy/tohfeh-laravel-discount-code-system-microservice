<?php

namespace Database\Seeders;

use App\Models\DiscountCodeGroups;
use Illuminate\Database\Seeder;

class DiscountCodeGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DiscountCodeGroups::factory()->create();
    }
}
