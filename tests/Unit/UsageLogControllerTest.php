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

    public function testUsageOfUser(): void
    {
        $usageLogs = UsageLog::all() ;
        $uuid = $usageLogs[9]->uuid;
        $code = $usageLogs[9]->code;
        $usage = (new UsageLogController)->UsageOfUser($code,$uuid);
        self::assertEquals(5, $usage);

    }
}
