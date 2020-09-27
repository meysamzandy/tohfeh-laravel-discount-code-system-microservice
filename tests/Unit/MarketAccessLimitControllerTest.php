<?php

namespace Tests\Unit;

use App\Models\MarketAccessLimit;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MarketAccessLimitControllerTest extends TestCase
{
    protected $model ;
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=MarketAccessLimitSeeder');
        $this->model = new MarketAccessLimit();
    }

    public function testInsertMarketAccessLimit(): void
    {
        $market = $this->model->insertMarketAccessLimit(1, 'cafebazaar', 1, 0, 13);
        self::assertNotNull($market);
        $this->assertDatabaseHas('market_access_limits', [
            'code_id' => $market['code_id'],
            'market_name' =>$market['market_name'],
            'version_major' =>$market['version_major'],
            'version_minor' => $market['version_minor'],
            'version_patch' =>$market['version_patch'],
        ]);

    }

    public function testSelectMarketAccessLimit(): void
    {
        $getFirstRowMarket = $this->model::query()->first();
        $hasMarketLimit = $this->model->selectMarketAccessLimit($getFirstRowMarket->code_id, $getFirstRowMarket->market_name, $getFirstRowMarket->version_major, $getFirstRowMarket->version_minor, $getFirstRowMarket->version_patch);
        self::assertTrue($hasMarketLimit);

        $hasMarketLimit = $this->model->selectMarketAccessLimit(1, 'myket', 1, 0, 1);
        self::assertFalse($hasMarketLimit);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
