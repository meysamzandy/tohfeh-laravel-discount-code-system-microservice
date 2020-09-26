<?php

namespace Tests\Unit;

use App\Http\Controllers\UsageLogController;
use App\Models\UsageLog;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UsageLogControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
    }

    public function testCountUsageLog(): void
    {
        $usageLogs = UsageLog::all();
        $uuid = $usageLogs[9]->uuid;
        $code = $usageLogs[9]->code;
        $usage = (new UsageLogController)->countUsageLog($code, $uuid);
        self::assertEquals(5, $usage);

    }

    public function testInsertUsageLog(): void
    {
        $store = (new UsageLogController)->insertUsageLog(1, 'test', 'uuid_test');
        self::assertNotNull($store);
        $this->assertDatabaseHas('usage_logs', [
            'code_id' => $store->code_id,
            'code' => $store->code,
            'uuid' => $store->uuid
        ]);
    }
}
