<?php

namespace Tests\Feature;

use App\Http\Controllers\ProcessCodeForAnonymousUser;
use App\Http\Helper\JwtHelper;
use App\Models\DiscountCode;
use App\Models\UsageLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProcessCodeForAnonymousUserTest extends TestCase
{
    public const PROCESS_CODE_URL = 'api/anonymous/code/';
    public const CODE_URL = 'api/admin/code';

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');


    }
    public function testCode(): void
    {
        // check if wrong url or there is no any code like this in db
        $url = self::PROCESS_CODE_URL . 'wewqew';
        $response = $this->post($url);
        $response->assertStatus(404);


        // create manual public with has market false code
        $data = [
            //code group
            'group_name' => 'manualtest',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'creation_code_count' => 5,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'CODE_TEST_50',
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

        ]; //code is CODE_TEST_50
        (new DiscountCode)->insertManualCode($data);
        // process manual public with has market false code
        $url = self::PROCESS_CODE_URL . 'CODE_TEST_50';
        $tokenData = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ];
        $jwt = JwtHelper::encodeJwt('HS512',config('settings.user_management_jwt.key'), $tokenData, 36000);
        $data = [
            'user_token' => $jwt,
            'market' => [
                "name" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(200, $response->status());
        self::assertFalse($responseData['body']['first_by']);
        self::assertEquals('CODE_TEST_50', $responseData['body']['code']);
        self::assertCount(1, $responseData['body']['features']);


        // count up usage_limit
        $code = DiscountCode::query()->where('code', 'CODE_TEST_50')->first();
        ++$code->usage_count;
        $code->save();
        // process manual public with has market false code
        $url = self::PROCESS_CODE_URL . 'CODE_TEST_50';
        $tokenData = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ];
        $jwt = JwtHelper::encodeJwt('HS512',config('settings.user_management_jwt.key'), $tokenData, 36000);
        $data = [
            'user_token' => $jwt,
            'market' => [
                "name" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(403, $response->status());
        self::assertEquals('سقف مجاز استفاده از این کد به پایان رسیده است.', $responseData['message']);


        // create manual public with has market true code not exist in market
        $data = [
            //code group
            'group_name' => 'manualtest',
            'series' => '',
            //code property
            'created_type' => 'manual', // if auto code should be empty
            'creation_code_count' => 5,
            'prefix' => 'test_',
            'stringType' => 0,
            'code' => 'CODE_TEST_51',
            'access_type' => 'public',
            'uuid_list' => '',
            'usage_limit' => 1,
            'usage_limit_per_user' => 1,
            'first_buy' => false,
            'has_market' => true,
            'market' => [
                ["market_name" => "myket",
                    "version_major" => 1,
                    "version_minor" => 2,
                    "version_patch" => 1
                ]
            ],

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

        ]; //code is CODE_TEST_51
        (new DiscountCode)->insertManualCode($data);
        // process manual public with has market false code
        $url = self::PROCESS_CODE_URL . 'CODE_TEST_51';
        $tokenData = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ];
        $jwt = JwtHelper::encodeJwt('HS512',config('settings.user_management_jwt.key'), $tokenData, 36000);
        $data = [
            'user_token' => $jwt,
            'market' => [
                "name" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(403, $response->status());
        self::assertEquals('استفاده از این کد، بر روی اپلیکیشنی که از آن استفاده میکنید، مجاز نیست.', $responseData['message']);




        // cancel code CODE_TEST_52
        $code = DiscountCode::query()->where('code', 'CODE_TEST_51')->first();
        $code->cancel_date = date('Y-m-d H:i:s', strtotime(Carbon::today()->subDays(5)));
        $code->save();
        // process manual public with has market false code
        $url = self::PROCESS_CODE_URL . 'CODE_TEST_51';
        $tokenData = [
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ];
        $jwt = JwtHelper::encodeJwt('HS512',config('settings.user_management_jwt.key'), $tokenData, 36000);
        $data = [
            'user_token' => $jwt,
            'market' => [
                "name" => "caffebazar",
                "version" => "1.0.5"
            ]
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(403, $response->status());




        // if market data is wrong
        // process manual public with has market false code
        $url = self::PROCESS_CODE_URL . 'CODE_TEST_51';
        $data = [
            'user_token' => 'ddssddd',
            'mdarket' => [
                "nadme" => "caffebazar",
                "vedsion" => "1.0.5"
            ]
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(400, $response->status());

    }

}
