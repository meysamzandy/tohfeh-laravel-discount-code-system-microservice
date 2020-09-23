<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Http\Helper\JwtHelper;

class JwtHelperTest extends TestCase
{

    public function testDecodeJwt(): void
    {
        $data = [
            'fake' => ' fake',
        ];
        $token = JwtHelper::encodeJwt($data, 5);
        self::assertIsString($token);

    }

    public function testEncodeJwt(): void
    {
        $token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJib2R5Ijp7ImZha2UiOiIgZmFrZSJ9LCJleHAiOjE1OTU5MjI4Mjl9.5L2pVBqSWS-yEMs3TGDdDu0RW1rBcbVyPCSXb5t6Bh2WZkVJWuodX6v3MIQ07Tk2vTWqeicLXwIVgl4PjNcBMA';
        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), $token);
        self::assertIsArray($data);
        self::assertFalse($data['result']);


        // Invalid header
        $token = 'eyJhbGciOiJIUzUxMiR5cCI6IkpXVCJ9.eyJib2R5Ijp7ImZha2UiOiIgZmFrZSJ9LCJleHAiOjE1OTU5MjI4Mjl9.5L2pVBqSWS-yEMs3TGDdDu0RW1rBcbVyPCSXb5t6Bh2WZkVJWuodX6v3MIQ07Tk2vTWqeicLXwIVgl4PjNcBMA';
        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), $token);
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Invalid header', $data['body']);


        // Invalid payload
        $token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJib2R5Ijp7ImZha2UiOiIgZmFrZSJ9LCJleHAiOjE2MA4NDA3NTJ9.P6x2rONFlTOiPN9gfW-rfTq227sDfYNZG5YkMiRfRRVtbf_tmFGO5NhZ3XVorxPfVdYJOa9nqKe2S4_v84cchQ';
        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), $token);
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Invalid payload', $data['body']);

        // Invalid signature
        $token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJib2R5Ijp7ImZha2UiOiIgZmFrZSJ9LCJleHAiOjE2MDA4NDA3NTJ9.P6x2rONFlTOiPN9gfW-rfTq227sDfYNZG5YkMiRfRRVtbf_tmFGO5NhZ3XorxPfVdYJOa9nqKe2S4_v84cchQ';
        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), $token);
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Invalid signature', $data['body']);


        $data = ['fake' => ' fake',];
        $token = JwtHelper::encodeJwt($data, 5);
        $data = JwtHelper::decodeJwt('', $token);
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Invalid signature', $data['body']);

        // Wrong number of segments

        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), 'sokssen');
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Wrong number of segments', $data['body']);

        //Token not provided
        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), '');
        self::assertIsArray($data);
        self::assertFalse($data['result']);
        self::assertEquals('Token not provided', $data['body']);

        
        $data = ['fake' => ' fake',];
        $token = JwtHelper::encodeJwt($data, 5);

        $data = JwtHelper::decodeJwt(config('settings.jwt.key'), $token);
        self::assertIsArray($data);
        self::assertTrue($data['result']);
        self::assertArrayHasKey('fake', $data['body']['body']);


        
    }
}
