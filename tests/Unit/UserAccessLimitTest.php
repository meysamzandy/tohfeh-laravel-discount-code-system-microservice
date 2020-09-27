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
        $this->model = new UserAccessLimit;
    }

    public function testInsertUserAccessLimit(): void
    {
        $userAccess = $this->model->insertUserAccessLimit(1, '2d3c9de4-3831-4988-8afb-710fda2e740c');
        self::assertNotNull($userAccess);
        $this->assertDatabaseHas('user_access_limits', [
            'code_id' => 1,
            'uuid' => '2d3c9de4-3831-4988-8afb-710fda2e740c',
        ]);
    }

    public function testSelectUserAccessLimit(): void
    {
        $userHasAccessRow = $this->model::all();
        $userHasAccess = $this->model->selectUserAccessLimit($userHasAccessRow[0]->code_id,$userHasAccessRow[0]->uuid);
        self::assertTrue($userHasAccess);

        $userHasAccess = $this->model->selectUserAccessLimit(1,'2d3c9de4-3831-4988-8afb-710fda2e740c');
        self::assertFalse($userHasAccess);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
