<?php

namespace Tests\Unit;

use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Error\Error;
use Tests\TestCase;

class DiscountCodeFeaturesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

    }
    public function testGroup(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeFeaturesSeeder');
        $feature =  DiscountCodeFeatures::all();
        $getGroup = (new DiscountCodeFeatures())->find($feature[0]->id)->group->group_name;
        self::assertNotNull($getGroup);
        self::assertIsString($getGroup);

    }

    public function testCreateFeature(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
        $group = DiscountCodeGroups::query()->get()->first();
        $featuresData= [
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                'code_type' => 'price',
                'percent' => 1,
                'limit_percent_price' => '',
                'price' => 1000,
                'description' => 'a sample text for description',
            ]
        ];
        // check if create feature
        $createFeature = (new DiscountCodeFeatures())->createFeature($featuresData, $group['id']);
        self::assertTrue($createFeature);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 1212,
            'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
            'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);

        // check if exception work
        Artisan::call('migrate:rollback');
        $createFeature = (new DiscountCodeFeatures())->createFeature($featuresData, $group['id']);
        self::assertFalse($createFeature);

    }

    public function testCheckFeatureBeforeAddToExistingCode(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
        $group = DiscountCodeGroups::query()->get()->first();
        $start_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))) ;
        $end_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))) ;
        $featuresData= [
            [
                'plan_id' => 1515,
                'start_time' => $start_date,
                'end_time' => $end_date,
                'code_type' => 'price',
                'percent' => 1,
                'limit_percent_price' => '',
                'price' => 1000,
                'description' => 'a sample text for description',
            ]
        ];
        (new DiscountCodeFeatures())->createFeature($featuresData, $group['id']);

        // plan id same and time has Common interval in start_time
        $features = [
            [
                'plan_id' => 1515,
                'start_time' => $start_date,
                'end_time' => $end_date,
            ]
        ];
        $checkResult = (new DiscountCodeFeatures())->checkFeatureBeforeAddToExistingCode($group['id'], $features);
        self::assertFalse($checkResult);

        // plan id same and time has Common interval in end_time
        $features = [
            [
                'plan_id' => 1515,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->subDays(5))),
                'end_time' => $end_date,
            ]
        ];
        $checkResult = (new DiscountCodeFeatures())->checkFeatureBeforeAddToExistingCode($group['id'], $features);
        self::assertFalse($checkResult);

        // feature can add
        $features = [
            [
                'plan_id' => 1616,
                'start_time' => $start_date,
                'end_time' => $end_date,
            ]
        ];
        $checkResult = (new DiscountCodeFeatures())->checkFeatureBeforeAddToExistingCode($group['id'], $features);
        self::assertTrue($checkResult);

    }

    public function testAddFeaturesToExistingCode(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
        $group = DiscountCodeGroups::query()->get()->first();
        $start_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))) ;
        $end_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))) ;

        // check if exception work
        $featuresData= [
            [
                'plan_id' => 2222,
                'start_time' => $start_date,
                'end_time' => $end_date,
                'code_type' => 'price',
                'percent' => 'dddd',
                'limit_percent_price' => '',
                'price' => 1000,
                'description' => 'a sample text for description',
            ]
        ];
        $addFeatures= (new DiscountCodeFeatures())->addFeaturesToExistingCode(2, $featuresData);
        self::assertFalse($addFeatures['status']);
        self::assertEquals(417, $addFeatures['statusCode']);
        $this->assertDatabaseMissing('discount_code_features', [
            'group_id' => $group->id,
            'plan_id' => 2222,
            'start_time' => $start_date,
            'code_type' => 'price',
            'percent' => 'dddd',
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);

        // add to db correctly
        $featuresData= [
            [
                'plan_id' => 2020,
                'start_time' => $start_date,
                'end_time' => $end_date,
                'code_type' => 'price',
                'percent' => 1,
                'limit_percent_price' => '',
                'price' => 1000,
                'description' => 'a sample text for description',
            ]
        ];
        $addFeatures= (new DiscountCodeFeatures())->addFeaturesToExistingCode($group->id, $featuresData);
        self::assertTrue($addFeatures['status']);
        self::assertEquals(201, $addFeatures['statusCode']);
        $this->assertDatabaseHas('discount_code_features', [
            'group_id' => $group->id,
            'plan_id' => 2020,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
    }


}
