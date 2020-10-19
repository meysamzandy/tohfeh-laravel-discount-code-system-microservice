<?php

namespace Tests\Feature;

use App\Http\Controllers\SuccessJobsController;
use App\Models\SuccessJobs;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SuccessJobsControllerTest extends TestCase
{
    public const JOBS_URL = 'api/admin/jobs';
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }
    public function testIndex(): void
    {

        SuccessJobs::create([
            'resultStats' => 1,
            'body' => 'test body',
            'message' => 'message test',
            'statusCode' => 201,
        ]);
        // check if wrong url
        $url = 'self::JOBS_URL';
        $response = $this->get($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::JOBS_URL;
        $response = $this->get($url);
        $response->assertStatus(403);

        // check list without params
        $url = self::JOBS_URL;
        $this->withoutMiddleware();
        $request = $this->get($url);
        $request->assertStatus(200);
        $request->assertExactJson(json_decode($request->getContent(), true));
        $data = json_decode($request->getContent(), true);
        self::assertEquals(1, $data['body']['current_page']);
        self::assertEquals(1, $data['body']['total']);

    }
}
