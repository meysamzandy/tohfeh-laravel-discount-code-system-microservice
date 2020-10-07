<?php

namespace Tests\Feature;

use App\Http\Controllers\CodeCallBack;
use App\Http\Helper\JwtHelper;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CodeCallBackTest extends TestCase
{
    public const CALLBACK_URL = 'api/discount/code/callback';

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');


    }

    public function testCallback(): void
    {
        // check if wrong url
        $url = 'self::CALLBACK_URL';
        $response = $this->post($url);
        $response->assertStatus(404);

        // if data object doesn't exist
        $url = self::CALLBACK_URL;
        $data = [
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(400, $response->status());
        self::assertEquals('مقدار برای آبجکت data یافت نشد', $responseData['message']);


        // if data token segment is wrong
        $url = self::CALLBACK_URL;
        $data = [
            'data' => 'sssss'
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(403, $response->status());
        self::assertEquals('Wrong number of segments', $responseData['message']);


        // if data token is wrong
        $url = self::CALLBACK_URL;
        $data = [
            'data' => 'sssss.aaaa.aaaa'
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(403, $response->status());
        self::assertEquals('Invalid header', $responseData['message']);


        // if data token is wrong
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeSeeder');

        // code exist in db
        $codeBeforeCallback = DiscountCode::all()[0];
        $tokenData = [
            'uuid'=> '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'code' => $codeBeforeCallback['code'],
            'usage_result' => false
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.client_jwt.key'), $tokenData, 36000);
        $url = self::CALLBACK_URL;
        $data = [
            'data' => $jwt
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(204, $response->status());
        $this->assertDatabaseMissing('discount_codes', [
            'code' => $codeBeforeCallback['code'],
            'usage_count' => $codeBeforeCallback['usage_count'] + 1
        ]);

        $this->assertDatabaseMissing('usage_logs', [
            'code_id' => $codeBeforeCallback['id'],
            'code' => $codeBeforeCallback['code'],
            'uuid' => $tokenData['uuid'],
        ]);



        // code exist in db
        $codeBeforeCallback = DiscountCode::all()[0];
        $tokenData = [
            'uuid'=> '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'code' => $codeBeforeCallback['code'],
            'usage_result' => true
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.client_jwt.key'), $tokenData, 36000);
        $url = self::CALLBACK_URL;
        $data = [
            'data' => $jwt
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(201, $response->status());
        $this->assertDatabaseHas('discount_codes', [
            'code' => $codeBeforeCallback['code'],
            'usage_count' => $codeBeforeCallback['usage_count'] + 1
        ]);

        $this->assertDatabaseHas('usage_logs', [
            'code_id' => $codeBeforeCallback['id'],
            'code' => $codeBeforeCallback['code'],
            'uuid' => $tokenData['uuid'],
        ]);


        // token Data is not valid
        $codeBeforeCallback = DiscountCode::all()[0];
        $tokenData = [
            'uuisd'=> '2d3c9de4-3831-4988-8afb-710fda2e740c',
            'cosde' => $codeBeforeCallback['code'],
            'usagse_result' => true
        ];
        $jwt = JwtHelper::encodeJwt(config('settings.client_jwt.key'), $tokenData, 36000);
        $url = self::CALLBACK_URL;
        $data = [
            'data' => $jwt
        ];
        $response = $this->post($url, $data);
        $responseData = json_decode($response->getContent(), true);
        self::assertEquals(400, $response->status());



    }
}
