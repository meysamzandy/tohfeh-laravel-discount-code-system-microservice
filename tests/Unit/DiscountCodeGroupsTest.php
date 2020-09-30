<?php

namespace Tests\Unit;

use App\Models\DiscountCodeGroups;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeGroupsTest extends TestCase
{

    public function testCodes(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');
        $group = DiscountCodeGroups::all();
        $data = (new DiscountCodeGroups)->find($group[0]->id)->codes()->first();
        self::assertNotNull( $data);
        self::assertArrayHasKey('code', $data);
        
    }

    public function testFeatures(): void
    {

        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeFeaturesSeeder');
        $group = DiscountCodeGroups::all();
        $data = (new DiscountCodeGroups)->find($group[0]->id)->features()->first();
        self::assertNotNull( $data);
        self::assertArrayHasKey('plan_id', $data);

    }
}
