<?php

namespace Database\Seeders;

use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Illuminate\Database\Seeder;

class DiscountCodeFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DiscountCodeFeatures::factory()->create(['group_id' => DiscountCodeGroups::factory()->create()]);
    }
}
