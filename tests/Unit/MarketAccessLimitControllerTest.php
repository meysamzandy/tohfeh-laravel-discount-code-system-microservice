<?php

namespace Tests\Unit;

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


    public function testCreateMarket(): void
    {
        // if has_market true
        $marketData = [
            [
                "market_name" => "myket",
                "version_major" => 1,
                "version_minor" => 2,
                "version_patch" => 0
            ],
            [
                "market_name" => "googlePlay",
                "version_major" => 1,
                "version_minor" => 0,
                "version_patch" => 2
            ]
        ];
        (new MarketAccessLimit)->createMarket(true, $marketData, 1);
        $this->assertDatabaseHas('market_access_limits', [
            'code_id' => 1,
            'market_name' => 'googlePlay',
            'version_major' => 1,
            'version_minor' => 0,
            'version_patch' => 2,
        ]);
        $marketData = [
            [
                "market_name" => "myket",
                "version_major" => 1,
                "version_minor" => 0,
                "version_patch" => 0
            ],

        ];
        // if has_market false
        (new MarketAccessLimit)->createMarket(false, $marketData, 1);
        $this->assertDatabaseMissing('market_access_limits', [
            'code_id' => 1,
            'market_name' => 'myket',
            'version_major' => 1,
            'version_minor' => 0,
            'version_patch' => 0,
        ]);
    }

    public function testSelectMarketAccessLimit(): void
    {
        $getFirstRowMarket = MarketAccessLimit::query()->first();
        $hasMarketLimit = (new MarketAccessLimit)->selectMarketAccessLimit($getFirstRowMarket->code_id, $getFirstRowMarket->market_name, $getFirstRowMarket->version_major, $getFirstRowMarket->version_minor, $getFirstRowMarket->version_patch);
        self::assertTrue($hasMarketLimit);

        $hasMarketLimit = (new MarketAccessLimit)->selectMarketAccessLimit(1, 'myket', 1, 0, 1);
        self::assertFalse($hasMarketLimit);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
