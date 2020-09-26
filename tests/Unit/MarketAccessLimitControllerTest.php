<?php

namespace Tests\Unit;

use App\Http\Controllers\MarketAccessLimitController;
use App\Models\MarketAccessLimit;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MarketAccessLimitControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh --seed --seeder=MarketAccessLimitSeeder');
    }

    public function testInsertMarketAccessLimit(): void
    {
        $market = (new MarketAccessLimitController)->insertMarketAccessLimit(1, 'cafebazaar', 1, 0, 13);
        self::assertNotNull($market);
        $this->assertDatabaseHas('market_access_limits', [
            'code_id' => $market->code_id,
            'market_name' =>$market->market_name,
            'version_major' =>$market->version_major,
            'version_minor' => $market->version_minor,
            'version_patch' =>$market->version_patch,
        ]);

    }

    public function testSelectMarketAccessLimit(): void
    {
        $getFirstRowMarket = MarketAccessLimit::query()->first();
        $hasMarketLimit = (new MarketAccessLimitController)->selectMarketAccessLimit($getFirstRowMarket->code_id, $getFirstRowMarket->market_name, $getFirstRowMarket->version_major, $getFirstRowMarket->version_minor, $getFirstRowMarket->version_patch);
        self::assertTrue($hasMarketLimit);

        $hasMarketLimit = (new MarketAccessLimitController)->selectMarketAccessLimit(1, 'myket', 1, 0, 1);
        self::assertFalse($hasMarketLimit);
    }
}
