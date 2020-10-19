<?php

namespace Tests\Feature;

use App\Http\Controllers\SuccessJobsController;
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
    public function testIndex()
    {
        // check if wrong url
        $url = 'self::JOBS_URL';
        $response = $this->get($url);
        $response->assertStatus(404);

        // check if token doesn't exist
        $url = self::JOBS_URL;
        $response = $this->get($url);
        $response->assertStatus(403);
    }
}
