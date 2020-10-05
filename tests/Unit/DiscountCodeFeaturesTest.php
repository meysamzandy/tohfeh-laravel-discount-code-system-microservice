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
        $feature = DiscountCodeFeatures::all();
        $getGroup = (new DiscountCodeFeatures())->find($feature[0]->id)->group->group_name;
        self::assertNotNull($getGroup);
        self::assertIsString($getGroup);

    }

    public function testCreateFeature(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
        $group = DiscountCodeGroups::query()->get()->first();
        $featuresData = [
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
        $start_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1)));
        $end_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5)));
        $featuresData = [
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
        $start_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1)));
        $end_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5)));

        // check if exception work
        $featuresData = [
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
        $addFeatures = (new DiscountCodeFeatures())->addFeaturesToExistingCode(2, $featuresData);
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
        $featuresData = [
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
        $addFeatures = (new DiscountCodeFeatures())->addFeaturesToExistingCode($group->id, $featuresData);
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

    public function testProcessFeatures(): void
    {
        $features = [
            // if feature expired
            [
                "id" => 67,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(3),
                "end_time" => Carbon::now()->subDays(1),
                "code_type" => "percent",
                "percent" => 10,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is price
            [
                "id" => 68,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(1),
                "end_time" => Carbon::now()->addDays(1),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature doesn't start yet
            [
                "id" => 69,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 121,
                "group_id" => 23,
                "plan_id" => 1211,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 122,
                "group_id" => 23,
                "plan_id" => 1212,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 123,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 124,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 125,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 126,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 10,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 127,
                "group_id" => 23,
                "plan_id" => 1214,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is free
            [
                "id" => 128,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is percent
            [
                "id" => 128,
                "group_id" => 23,
                "plan_id" => 1245,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
        ];
        $processFeatures = (new DiscountCodeFeatures())->processFeatures($features);
        self::assertIsArray($processFeatures);
        self::assertCount(5, $processFeatures[1210]);
        self::assertCount(1, $processFeatures[1211]);
        self::assertCount(1, $processFeatures[1212]);
        self::assertCount(3, $processFeatures[1213]);
        self::assertCount(1, $processFeatures[1214]);
        self::assertCount(1, $processFeatures[1245]);
        self::assertFalse($processFeatures[1210][0]['feature_status']);
        self::assertTrue($processFeatures[1210][1]['feature_status']);
        self::assertFalse($processFeatures[1210][2]['feature_status']);
        self::assertFalse($processFeatures[1210][3]['feature_status']);
        self::assertTrue($processFeatures[1210][4]['feature_status']);
        self::assertFalse($processFeatures[1211][0]['feature_status']);
        self::assertTrue($processFeatures[1212][0]['feature_status']);
        self::assertTrue($processFeatures[1213][0]['feature_status']);
        self::assertFalse($processFeatures[1213][1]['feature_status']);
        self::assertFalse($processFeatures[1213][2]['feature_status']);
        self::assertFalse($processFeatures[1214][0]['feature_status']);
        self::assertTrue($processFeatures[1245][0]['feature_status']);
    }

    public function testPrepareFeaturesToResponse(): void
    {
        $features = [
            // if feature expired
            [
                "id" => 67,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(3),
                "end_time" => Carbon::now()->subDays(1),
                "code_type" => "percent",
                "percent" => 10,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is price
            [
                "id" => 68,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(1),
                "end_time" => Carbon::now()->addDays(1),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature doesn't start yet
            [
                "id" => 69,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 121,
                "group_id" => 23,
                "plan_id" => 1211,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 122,
                "group_id" => 23,
                "plan_id" => 1212,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 123,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 124,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "price",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => 1000,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 125,
                "group_id" => 23,
                "plan_id" => 1213,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 126,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 10,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 127,
                "group_id" => 23,
                "plan_id" => 1214,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is free
            [
                "id" => 128,
                "group_id" => 23,
                "plan_id" => 1210,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "free",
                "percent" => null,
                "limit_percent_price" => null,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            // if feature type is percent
            [
                "id" => 128,
                "group_id" => 23,
                "plan_id" => 1245,
                "start_time" => Carbon::now()->subDays(2),
                "end_time" => Carbon::now()->subDays(1),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
            [
                "id" => 180,
                "group_id" => 23,
                "plan_id" => 1245,
                "start_time" => Carbon::now()->addDays(2),
                "end_time" => Carbon::now()->addDays(4),
                "code_type" => "percent",
                "percent" => 25,
                "limit_percent_price" => 1000,
                "price" => null,
                "description" => "توضیح ندارد",
                "created_at" => "2020-10-04 14:31:31",
                "updated_at" => "2020-10-04 14:31:31",
            ],
        ];
        $prepareFeatures = (new DiscountCodeFeatures())->prepareFeaturesToResponse($features);
        self::assertIsArray($prepareFeatures);
        self::assertCount(6, $prepareFeatures);
        self::assertEquals(1210,$prepareFeatures[0]['plan_id']);
        self::assertTrue($prepareFeatures[0]['feature_status']);
        self::assertEquals(1212,$prepareFeatures[2]['plan_id']);
        self::assertTrue($prepareFeatures[2]['feature_status']);

        self::assertEquals(1213,$prepareFeatures[3]['plan_id']);
        self::assertTrue($prepareFeatures[3]['feature_status']);

        self::assertEquals(1214,$prepareFeatures[4]['plan_id']);
        self::assertFalse($prepareFeatures[4]['feature_status']);

        self::assertEquals(1245,$prepareFeatures[5]['plan_id']);
        self::assertFalse($prepareFeatures[5]['feature_status']);

    }

}
