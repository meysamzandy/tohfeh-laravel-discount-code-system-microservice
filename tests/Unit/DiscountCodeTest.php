<?php

namespace Tests\Unit;

use App\Models\DiscountCode;
use App\Models\UserAccessLimit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function testInsertManualCode(): void
    {

        // insert code correctly
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
            ],
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
            'market' => [
                'market_name' => '',
                'version_major' => '',
                'version_minor' => '',
                'version_patch' => '',
            ],
//            // code feature property
            'features' => [
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

            ]

        ];
        $manual = (new DiscountCode())->insertManualCode($data);
        self::assertTrue($manual['resultStats']);
        self::assertEquals(201, $manual['statusCode']);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'test',
        ]);
        $this->assertDatabaseHas('discount_codes', [
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 1212,
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);


        // check if code is exist
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
                '2d3c9de4-3831-4988-8afb-710fda2e740c',
            ],
            'usage_limit' => 1000,
            'usage_limit_per_user' => 1000,
            'first_buy' => true,
            'has_market' => true,
            'market' => [
                'market_name' => '',
                'version_major' => '',
                'version_minor' => '',
                'version_patch' => '',
            ],
//            // code feature property
            'features' => [
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

            ]

        ];
        $manual = (new DiscountCode())->insertManualCode($data);
        self::assertFalse($manual['resultStats']);
        self::assertEquals(417, $manual['statusCode']);
        $this->assertDatabaseMissing('discount_codes', [
            'created_type' => 'manual', // if auto code should be empty
            'code' => 'DDDDDDD',
            'access_type' => 'private',
            'usage_limit' => 1000,
            'usage_limit_per_user' => 1000,
            'first_buy' => true,
            'has_market' => true,
        ]);



    }

    public function testCreateCode(): void
    {
        // check auto code
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 10,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => '',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market' => [
                [
                    'market_name' => 'myket',
                    'version_major' => 1,
                    'version_minor' => 10,
                    'version_patch' => 0,
                ]
            ],

//            // code feature property

            'features' => [
                [
                    'plan_id' => 1919,
                    'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                    'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                    'code_type' => 'price',
                    'percent' => 1,
                    'limit_percent_price' => '',
                    'price' => 1000,
                    'description' => 'a sample text for description',
                ]

            ]

        ];
        $auto = (new DiscountCode())->createCode($data);
        self::assertTrue($auto['resultStats']);
        self::assertEquals(201, $auto['statusCode']);

        // check manual code
        $data = [
            //code group
            'group_name' => 'test',
            'series' => 'sss',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'creation_code_count' => 10,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'sadsdasdsad',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market' => [
                [
                    'market_name' => 'myket',
                    'version_major' => 1,
                    'version_minor' => 10,
                    'version_patch' => 0,
                ]
            ],

//            // code feature property

            'features' => [
                [
                    'plan_id' => 1919,
                    'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                    'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                    'code_type' => 'price',
                    'percent' => 1,
                    'limit_percent_price' => '',
                    'price' => 1000,
                    'description' => 'a sample text for description',
                ]

            ]

        ];
        $auto = (new DiscountCode())->createCode($data);
        self::assertTrue($auto['resultStats']);
        self::assertEquals(201, $auto['statusCode']);

        // if created_type not in auto && manual
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'noting', // if auto code should be empty
            'creation_code_count' => 10,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'vasdsa',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market' => [
                [
                    'market_name' => 'myket',
                    'version_major' => 1,
                    'version_minor' => 10,
                    'version_patch' => 0,
                ]
            ],

//            // code feature property

            'features' => [
                [
                    'plan_id' => 1919,
                    'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                    'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                    'code_type' => 'price',
                    'percent' => 1,
                    'limit_percent_price' => '',
                    'price' => 1000,
                    'description' => 'a sample text for description',
                ]

            ]

        ];
        $auto = (new DiscountCode())->createCode($data);
        self::assertFalse($auto['resultStats']);
        self::assertEquals(417, $auto['statusCode']);
    }

    public function testUsageLogs(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
        $codes = DiscountCode::all();
        $getUsers = (new DiscountCode())->find($codes[0]->id)->usageLogs;
        self::assertNotNull($getUsers[0]['uuid']);
        self::assertIsString($getUsers[0]['uuid']);
    }
    public function testUsers(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=UserAccessLimitSeeder');
        $codes = DiscountCode::all();
        $getUsers = (new DiscountCode())->find($codes[0]->id)->users;
        self::assertNotNull($getUsers[0]['uuid']);
        self::assertIsString($getUsers[0]['uuid']);
    }

    public function testMarkets(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=MarketAccessLimitSeeder');
        $codes = DiscountCode::all();
        $getMarket = (new DiscountCode())->find($codes[0]->id)->markets;
        self::assertNotNull($getMarket[0]['market_name']);
        self::assertIsString($getMarket[0]['market_name']);
    }

    public function testInsertAutoCode(): void
    {

// insert code correctly
        $countDiscountCode = DiscountCode::query()->count();
        $data = [
            //code group
            'group_name' => 'test',
            'series' => '',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 10,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => '',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market' => [
                [
                    'market_name' => 'myket',
                    'version_major' => 1,
                    'version_minor' => 10,
                    'version_patch' => 0,
                ]
            ],

//            // code feature property

            'features' => [
                [
                    'plan_id' => 1919,
                    'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                    'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                    'code_type' => 'price',
                    'percent' => 1,
                    'limit_percent_price' => '',
                    'price' => 1000,
                    'description' => 'a sample text for description',
                ]

            ]

        ];
        $auto = (new DiscountCode())->insertAutoCode($data);
        self::assertTrue($auto['resultStats']);
        self::assertEquals(201, $auto['statusCode']);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'test',
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 1919,
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
        $this->assertDatabaseCount('discount_codes', 10);
    }

    public function testGroup(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');
        $codes = DiscountCode::all();
        $getGroup = (new DiscountCode())->find($codes[0]->id)->group->group_name;
        self::assertNotNull($getGroup);
        self::assertIsString($getGroup);

    }

    public function testGetParams(): void
    {
        $params = (new DiscountCode)->getParams();
        self::assertIsArray($params);
        self::assertNotNull($params);
    }
}
