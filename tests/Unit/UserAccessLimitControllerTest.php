<?php

namespace Tests\Unit;

use App\Http\Controllers\UserAccessLimitController;
use App\Models\UserAccessLimit;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserAccessLimitControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=UserAccessLimitSeeder');
    }

    public function testInsertUserAccessLimit(): void
    {
        $userAccess = (new UserAccessLimitController)->insertUserAccessLimit(1, '2d3c9de4-3831-4988-8afb-710fda2e740c');
        self::assertNotNull($userAccess);
        $this->assertDatabaseHas('user_access_limits', [
            'code_id' => 1,
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ]);
    }

    public function testSelectUserAccessLimit(): void
    {
        $userHasAccessRow = UserAccessLimit::all();
        $userHasAccess = (new UserAccessLimitController())->selectUserAccessLimit($userHasAccessRow[0]->code_id,$userHasAccessRow[0]->uuid);
        self::assertTrue($userHasAccess);

        $userHasAccess = (new UserAccessLimitController())->selectUserAccessLimit(1,'2d3c9de4-3831-4988-8afb-710fda2e740c');
        self::assertFalse($userHasAccess);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
