<?php

namespace Tests\Feature;

use App\Http\Controllers\DiscountCodeGroupController;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeGroupControllerTest extends TestCase
{
    public const GROUP_URL = 'api/admin/group';
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }
    public function testIndex(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
        // check if wrong url
        $url = 'self::GROUP_URL';
        $response = $this->get($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::GROUP_URL;
        $response = $this->get($url);
        $response->assertStatus(403);

        // check list without params
        $url = self::GROUP_URL;
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(1, $data['body']['total']);

        // check list with page and limit params
        $url = self::GROUP_URL . '?page=1&limit=1';
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(1, $data['body']['last_page']);
        self::assertEquals(1, $data['body']['total']);

        // check list with page and limit params
        $url = self::GROUP_URL . '?page=1&limit=10&id=1&op_id==';
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

}
