<?php

namespace Tests\Unit;

use App\Http\Controllers\DiscountCodeGroupsController;
use App\Models\DiscountCodeGroups;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DiscountCodeGroupsControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=DiscountCodeGroupsSeeder');
    }

    public function testInsertGroup(): void
    {
        $insertGroup = (new DiscountCodeGroupsController())->insertGroup('test group name');
        self::assertNotNull($insertGroup);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'test group name',
            'series' => null
        ]);

        $insertGroup = (new DiscountCodeGroupsController())->insertGroup('test group name1','test_series');
        self::assertNotNull($insertGroup);
        $this->assertDatabaseHas('discount_code_groups', [
            'group_name' => 'test group name1',
            'series' => 'test_series'
        ]);


    }
}
