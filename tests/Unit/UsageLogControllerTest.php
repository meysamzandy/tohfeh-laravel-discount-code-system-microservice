<?php

namespace Tests\Unit;

use App\Models\UsageLog;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UsageLogControllerTest extends TestCase
{
    protected $model ;
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
        $this->model = new UsageLog();
    }

    public function testCountUsageLog(): void
    {
        $usageLogs = $this->model::all();
        $uuid = $usageLogs[9]->uuid;
        $code = $usageLogs[9]->code;
        $usage = $this->model->countUsageLog($code, $uuid);
        self::assertEquals(5, $usage);

    }

    public function testInsertUsageLog(): void
    {
        $store = $this->model->insertUsageLog(1, 'test', 'uuid_test');
        self::assertNotNull($store);
        $this->assertDatabaseHas('usage_logs', [
            'code_id' => $store['code_id'],
            'code' => $store['code'],
            'uuid' => $store['uuid']
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
