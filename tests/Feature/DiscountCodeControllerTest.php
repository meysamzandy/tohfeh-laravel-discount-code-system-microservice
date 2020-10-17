<?php

namespace Tests\Feature;

use App\Http\Controllers\DiscountCodeController;
use App\Http\Helper\JwtHelper;
use App\Models\DiscountCode;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class DiscountCodeControllerTest extends TestCase
{
    public const CODE_URL = 'api/admin/code';
    public const MASSIVE_CODE_URL = 'api/create/code';

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

    public function testUpdate(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');
        $getOneCode = DiscountCode::find(1);
        // check if wrong url
        $url = self::CODE_URL;
        $response = $this->put($url);
        $response->assertStatus(405);


        // check if token doesn't exist
        $url = self::CODE_URL . '/' . 1;
        $response = $this->put($url);
        $response->assertStatus(403);


        // check data validations
        $this->withoutMiddleware();
        $data = [
        ];
        $response = $this->put($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(400);

        // check if id doesn't exist
        $url = self::CODE_URL . '/' . 3000;
        $this->withoutMiddleware();
        $data = [
            'usage_limit' => 22,
        ];
        $response = $this->put($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(404);

        //   put data correctly
        $url = self::CODE_URL . '/' . $getOneCode->id;
        $this->withoutMiddleware();
        $data = [
            'usage_limit' => 22,
        ];
        $response = $this->put($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(202);
        $this->assertDatabaseHas('discount_codes', [
            'id' => $getOneCode->id,
            'usage_limit' => 22,
        ]);

        //   get exception error
        Artisan::call('migrate:rollback');
        $url = self::CODE_URL . '/' . $getOneCode->id;
        $this->withoutMiddleware();
        $data = [
            'usage_limit' => 25,
        ];
        $response = $this->put($url, $data);
        $responseData = json_decode($response->getContent(), true);
        $response->assertStatus(417);


    }

    public function testIndex(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');
        // check if wrong url
        $url = 'self::CODE_URL';
        $response = $this->get($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::CODE_URL;
        $response = $this->get($url);
        $response->assertStatus(403);

        // check list without params
        $url = self::CODE_URL;
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(2, $data['body']['total']);

        // check list with page and limit params
        $url = self::CODE_URL . '?page=1&limit=1';
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(2, $data['body']['last_page']);
        self::assertEquals(2, $data['body']['total']);

        // check list with page and limit params
        $url = self::CODE_URL . '?page=1&limit=10&id=1&op_id==';
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['data'][0]['id']);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(1, $data['body']['last_page']);
        self::assertEquals(1, $data['body']['total']);
    }

    public function testCreate(): void
    {
        // check if wrong url
        $url = 'self::MASSIVE_CODE_URL';
        $response = $this->post($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::MASSIVE_CODE_URL;
        $response = $this->post($url);
        $response->assertStatus(403);

        // check data validations
        $url = self::MASSIVE_CODE_URL;
        $this->withoutMiddleware();
        $data = [

        ];
        $response = $this->post($url, $data);
        $response->assertStatus(400);

        // check feature array if is wrong
        $url = self::MASSIVE_CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'manualtest',
            'series' => 'test',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 1,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'CODE_TEST',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c'
            ],
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


        // create one auto code successfully for first time in series
        $url = self::MASSIVE_CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'autotest',
            'series' => 'test',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 1,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => '',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c'
            ],
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
        $response->assertStatus(201);
        self::assertArrayHasKey('code', $responseData['body']);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'autotest',
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 2222,
            'code_type' => 'price',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
        $this->assertDatabaseCount('discount_code_groups', 1);
        $this->assertDatabaseCount('discount_code_features', 1);
        $this->assertDatabaseCount('discount_codes', 1);


        // create one auto code successfully for second time in series
        $url = self::MASSIVE_CODE_URL;
        $this->withoutMiddleware();
        $data = [
            //code group
            'group_name' => 'autotest',
            'series' => 'test',
            //code property
            'created_type' => 'auto', // if auto code should be empty
            'creation_code_count' => 1,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => '',
            'access_type' => 'private',
            'uuid_list' => [
                '2d3c9de4-3831-4988-8afb-710fda2e740c'
            ],
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
        $response->assertStatus(201);
        self::assertArrayHasKey('code', $responseData['body']);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'autotest',
        ]);
        $this->assertDatabaseHas('discount_code_features', [
            'plan_id' => 2222,
            'code_type' => 'price',
            'price' => 1000,
            'description' => 'a sample text for description',
        ]);
        $this->assertDatabaseCount('discount_code_groups', 1);
        $this->assertDatabaseCount('discount_code_features', 1);
        $this->assertDatabaseCount('discount_codes', 2);
    }

    public function testDestroy(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');
        $getCodes = DiscountCode::all();
        // check if wrong url
        $url = self::CODE_URL;
        $response = $this->delete($url);
        $response->assertStatus(405);


        // check if token doesn't exist
        $url = self::CODE_URL . '/' . 1;
        $response = $this->delete($url);
        $response->assertStatus(403);


        // check if id doesn't exist
        $url = self::CODE_URL . '/' . 12345678;
        $data = [
            'password' => config('settings.admin_jwt.password')
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.admin_jwt.key'), $data, 360000);
        $response = $this->delete($url, [], ['token' => $jwt]);
        $response->assertStatus(404);


        // delete one code correctly
        $url = self::CODE_URL . '/' . $getCodes[0]['id'];
        $data = [
            'password' => config('settings.admin_jwt.password')
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.admin_jwt.key'), $data, 360000);
        $response = $this->delete($url, [], ['token' => $jwt]);
        self::assertEquals(1, $getCodes[0]['id']);
        $this->assertDatabaseMissing('discount_codes', [
            'id' => $getCodes[0]['id']
        ]);
        $this->assertDatabaseHas('discount_code_groups', [
            'id' => $getCodes[0]['group_id']
        ]);
        $response->assertStatus(204);


        // delete last code correctly and delete group
        $url = self::CODE_URL . '/' . $getCodes[1]['id'];
        $data = [
            'password' => config('settings.admin_jwt.password')
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.admin_jwt.key'), $data, 360000);

        $response = $this->delete($url, [], ['token' => $jwt]);
        self::assertEquals(1, $getCodes[0]['id']);
        $this->assertDatabaseMissing('discount_codes', [
            'id' => $getCodes[1]['id']
        ]);
        $this->assertDatabaseMissing('discount_code_groups', [
            'id' => $getCodes[1]['group_id']
        ]);
        $response->assertStatus(204);



        
    }

}
