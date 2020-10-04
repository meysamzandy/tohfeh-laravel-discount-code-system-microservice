<?php

namespace Tests\Feature;

use App\Http\Controllers\DiscountCodeController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeControllerTest extends TestCase
{
    public const CODE_URL = 'api/admin/code';

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function testStore(): void
    {
        // check if wrong url
        $url = 'self::CODE_URL';
        $response = $this->post($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::CODE_URL;
        $response = $this->post($url);
        $response->assertStatus(403);

        // check data validations
        $url = self::CODE_URL;
        $this->withoutMiddleware();
        $data = [

        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(400);


        // check feature array
        $url = self::CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'manualtest',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'creation_code_count' => 5,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'CODE_TEST',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
            'market' => '',

            // code feature property

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
                ],
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
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(400);

        $this->assertDatabaseCount('discount_code_groups', 0);
        $this->assertDatabaseCount('discount_code_features', 0);
        $this->assertDatabaseCount('discount_codes', 0);


        // create auto code successfully for 10
        $url = self::CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'autotest',
            'series' => '',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 5,
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

            // code feature property

            'features' => [
                [
                    'plan_id' => 2222,
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
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(200);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'autotest',
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 2222,
            'code_type' => 'price',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
        $this->assertDatabaseHas('success_jobs', [
            'resultStats' => 1,
            'statusCode' => 201,
        ]);
        $this->assertDatabaseCount('discount_code_groups', 1);
        $this->assertDatabaseCount('discount_code_features', 1);
        $this->assertDatabaseCount('discount_codes', 5);
        $this->assertDatabaseCount('success_jobs', 1);


        // create manual code successfully for 1
        $url = self::CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'manualtest',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'creation_code_count' => 5,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'CODE_TEST',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => false,
            'market' => '',

            // code feature property

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
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(201);

        $this->assertDatabaseCount('discount_code_groups', 2);
        $this->assertDatabaseCount('discount_code_features', 2);
        $this->assertDatabaseCount('discount_codes', 6);
        $this->assertDatabaseHas('discount_codes', [
            'code' => 'CODE_TEST',
        ]);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'manualtest',
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 2222,
            'code_type' => 'price',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
    }

}
