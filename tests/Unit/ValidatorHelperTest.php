<?php

namespace Tests\Unit;

use App\Http\Helper\ValidatorHelper;
use App\Models\DiscountCode;
use App\Models\DiscountCodeFeatures;
use App\Models\DiscountCodeGroups;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ValidatorHelperTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
//        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
    }

    public function testCreationCodeValidator(): void
    {
        //check when all validation failed
        $data = [
            //code group
            'group_name' => '',
            'series' => '',
            //code property
            'created_type' => 'manuasl', // if auto code should be empty
            'code' => '',
            'access_type' => 'privatse',
            'uuid_list' => '',
            'usage_limit' => 0,
            'usage_limit_per_user' => 0,
            'first_buy' => 'false',
            'has_market' => true,
            'market_name' => '',
            'version_major' => '',
            'version_minor' => '',
            'version_patch' => '',
//            // code feature property
            'plan_id' => 'fds',
            'start_time' => date('Y-m-d H:i:s', strtotime('2020-09-22 10:25:38')),
            'end_time' => date('Y-m-d H:i:s', strtotime('2020-09-25 10:25:38')),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => '',
            'description' => 'a sample text for descriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescrip
            tiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondesc
            descriptiondescriptiondescriptiondescriptionriptiondescriptiondescriptiondescriptiondescription',

        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertFalse($result->passes());

        //check when end_time is lower than start_time
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
            'market_name' => '',
            'version_major' => '',
            'version_minor' => '',
            'version_patch' => '',
//            // code feature property
            'plan_id' => 1212,
            'start_time' => date('Y-m-d H:i:s', strtotime('2020-09-22 10:25:38')),
            'end_time' => date('Y-m-d H:i:s', strtotime('2020-09-20 10:25:38')),
            'code_type' => 'price',
            'percent' => 1,
            'limit_percent_price' => '',
            'price' => 1000,
            'description' => 'a sample text for description',

        ];
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertFalse($result->passes());

        //check when has_market is false & access_type is private & created_type is manual
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
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertTrue($result->passes());

        //check when created_type is auto & access_type is public &  has_market is true
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
        $result = (new ValidatorHelper)->creationCodeValidator($data);
        self::assertTrue($result->passes());

    }


    public function testCreationFeatureValidator(): void
    {
        //check when all validation failed
        $data = [

        ];
        $result = (new ValidatorHelper)->creationFeatureValidator($data);
        self::assertFalse($result->passes());

        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeFeaturesSeeder');
        $feature = DiscountCodeFeatures::all();
        $getGroup = (new DiscountCodeFeatures())->find($feature[0]->id)->group->id;
        $data = [
            'group_id' => $getGroup,
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
                ],
            ]
        ];
        $result = (new ValidatorHelper)->creationFeatureValidator($data);
        self::assertTrue($result->passes());

    }


    public function testValidateFeatureArray(): void
    {
        // plan id same and time has Common interval in start_time
        $features = [
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            ],
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->subDays(6))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(8))),
            ],
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(4))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(10))),
            ]

        ];
        $result = (new ValidatorHelper)->validateFeatureArray($features);
        self::assertFalse($result);

        // plan id same and time has Common interval in end_time
        $features = [
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            ],
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(3))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(8))),
            ],
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(9))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(10))),
            ]

        ];
        $result = (new ValidatorHelper)->validateFeatureArray($features);
        self::assertFalse($result);


        // plan id different but time has Common interval
        $features = [
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
            ],
            [
                'plan_id' => 1213,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(3))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(8))),
            ],
            [
                'plan_id' => 1214,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(7))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(10))),
            ]

        ];
        $result = (new ValidatorHelper)->validateFeatureArray($features);
        self::assertTrue($result);

        // features are valid
        $features = [
            [
                'plan_id' => 1212,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(1))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(3))),
            ],
            [
                'plan_id' => 1213,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(5))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(7))),
            ],
            [
                'plan_id' => 1214,
                'start_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(8))),
                'end_time' => date('Y-m-d H:i:s', strtotime(Carbon::today()->addDays(10))),
            ]

        ];
        $result = (new ValidatorHelper)->validateFeatureArray($features);
        self::assertTrue($result);
    }

    public function testCodeProcessingValidator(): void
    {
        // version is not correct
        $CodeProcessing = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'market' => [
                "name" => "caffebazar",
                "versaion" => "1.0.5"
            ]
        ];
        $result = (new ValidatorHelper)->marketValidator($CodeProcessing);
        self::assertFalse($result->passes());

        // name is not correct
        $CodeProcessing = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'market' => [
                "namse" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $result = (new ValidatorHelper)->marketValidator($CodeProcessing);
        self::assertFalse($result->passes());

        // CodeProcessing is not correct
        $CodeProcessing = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'markset' => [
                "namse" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $result = (new ValidatorHelper)->marketValidator($CodeProcessing);
        self::assertFalse($result->passes());

        // CodeProcessing data is correct
        $CodeProcessing = [
            'user_token' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'market' => [
                "name" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $result = (new ValidatorHelper)->marketValidator($CodeProcessing);
        self::assertTrue($result->passes());
    }

    public function testUpdateCodeValidator(): void
    {
        // version is not correct
        $updateCodeData = [
            'usage_limit' => '',
        ];
        $result = (new ValidatorHelper)->updateCodeValidator($updateCodeData);
        self::assertFalse($result->passes());

        // version is correct
        $updateCodeData = [
            'usage_limit' => 22,
        ];
        $result = (new ValidatorHelper)->updateCodeValidator($updateCodeData);
        self::assertTrue($result->passes());
    }

    public function testCallBackDataValidator(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');

        // code exist in db
        $code = DiscountCode::all()[0];
        $data = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'code' => $code['code'],
            'usage_result' => true
        ];
        $result = (new ValidatorHelper)->callBackDataValidator($data);
        self::assertTrue($result->passes());

        // code doesn't exist in db
        $data = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'code' => 'ddaaddd',
            'usage_result'
        ];
        $result = (new ValidatorHelper)->callBackDataValidator($data);
        self::assertFalse($result->passes());

        // check if data all in valid
        $data = [
        ];
        $result = (new ValidatorHelper)->callBackDataValidator($data);
        self::assertFalse($result->passes());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
