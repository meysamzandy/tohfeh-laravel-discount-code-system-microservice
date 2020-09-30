<?php

namespace Tests\Unit;

use App\Models\UsageLog;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UsageLogTest extends TestCase
{
    protected $model ;
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
    }

    public function testCountUsageLog(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
        $usageLogs = UsageLog::all();
        $uuid = $usageLogs[9]->uuid;
        $code = $usageLogs[9]->code;
        $usage = (new UsageLog)->countUsageLog($code, $uuid);
        self::assertEquals(5, $usage);

        // check if exception work
        Artisan::call('migrate:rollback');
        $usage = (new UsageLog)->countUsageLog(1, 'test');
        self::assertEquals(0, $usage);

    }

    public function testInsertUsageLog(): void
    {
        Artisan::call('migrate:refresh --seed --seeder=UsageLogSeeder');
        $store = (new UsageLog)->insertUsageLog(1, 'test', 'uuid_test');
        self::assertNotNull($store);
        $this->assertDatabaseHas('usage_logs', [
            'code_id' => $store['code_id'],
            'code' => $store['code'],
            'uuid' => $store['uuid']
        ]);
        // check if exception work
        Artisan::call('migrate:rollback');
        $store = (new UsageLog)->insertUsageLog(1, 'test', 'uuid_test1');
        self::assertFalse($store);

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
