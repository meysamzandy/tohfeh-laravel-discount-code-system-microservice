<?php

namespace Tests\Unit;

use App\Models\UserAccessLimit;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserAccessLimitTest extends TestCase
{
    protected $model ;
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=UserAccessLimitSeeder');
    }

    public function testCreateUserAccess(): void
    {
        $userListData = [
            "2d3c9de4-3831-4988-8afb-710fda2e250a",
            "2d3c9de4-3831-4988-8afb-710fda2e260b",
            "2d3c9de4-3831-4988-8afb-710fda2e270c"
        ];

        (new UserAccessLimit)->createUserAccess('private', $userListData, 1);
        $this->assertDatabaseHas('user_access_limits', [
            'code_id' => 1,
            'uuid' => "2d3c9de4-3831-4988-8afb-710fda2e270c"

        ]);

        $userListData = [
            "2d3c9de4-3831-4988-8afb-710fda2e390a",
        ];
        (new UserAccessLimit)->createUserAccess('public', $userListData, 1);
        $this->assertDatabaseMissing('user_access_limits', [
            'code_id' => 1,
            'uuid' => "2d3c9de4-3831-4988-8afb-710fda2e390a"

        ]);
    }

    public function testSelectUserAccessLimit(): void
    {
        $userHasAccessRow = UserAccessLimit::all();
        $userHasAccess = (new UserAccessLimit)->selectUserAccessLimit($userHasAccessRow[0]->code_id,$userHasAccessRow[0]->uuid);
        self::assertTrue($userHasAccess);

        $userHasAccess = (new UserAccessLimit)->selectUserAccessLimit(1,'2d3c9de4-3831-4988-8afb-710fda2e740c');
        self::assertFalse($userHasAccess);

        // check if exception work
        Artisan::call('migrate:rollback');
        $userHasAccess = (new UserAccessLimit)->selectUserAccessLimit(1,'2d3c9de4-3831-4988-8afb-sss');
        self::assertFalse($userHasAccess);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
