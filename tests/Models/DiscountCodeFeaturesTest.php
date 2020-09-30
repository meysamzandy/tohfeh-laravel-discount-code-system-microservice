<?php

namespace Tests\Unit;

use App\Models\DiscountCodeFeatures;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeFeaturesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeFeaturesSeeder');
    }
    public function testGroup(): void
    {
        $feature =  DiscountCodeFeatures::all();
        $getGroup = (new DiscountCodeFeatures())->find($feature[0]->id)->group->group_name;
        self::assertNotNull($getGroup);
        self::assertIsString($getGroup);
    }

    public function testCreateFeature(): void
    {

    }

    public function testAddFeaturesToExistingCode(): void
    {

    }



    public function testCheckFeatureBeforeAddToExistingCode(): void
    {

    }
}
