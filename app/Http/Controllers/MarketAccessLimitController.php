<?php

namespace App\Http\Controllers;

use App\Models\MarketAccessLimit;
use Exception;

class MarketAccessLimitController extends Controller
{

    /**
     * @param int $code_id
     * @param string $market_name
     * @param int $version_major
     * @param int $version_minor
     * @param int $version_patch
     * @return object|null
     */
    public function insertMarketAccessLimit(int $code_id, string $market_name, int $version_major, int $version_minor, int $version_patch)
    {
        try {
            return MarketAccessLimit::create([
                'code_id' => $code_id,
                'market_name' => $market_name,
                'version_major' => $version_major,
                'version_minor' => $version_minor,
                'version_patch' => $version_patch,
            ]);
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * @param $code_id
     * @param $market_name
     * @param $version_major
     * @param $version_minor
     * @param $version_patch
     * @return bool
     */
    public function selectMarketAccessLimit($code_id, $market_name, $version_major, $version_minor, $version_patch): bool
    {
        try {
            return MarketAccessLimit::query()->where([
                'code_id' => $code_id,
                'market_name' => $market_name,
                'version_major' => $version_major,
                'version_minor' => $version_minor,
                'version_patch' => $version_patch,
            ])->exists();
        } catch (Exception $e) {
            return false ;
        }

    }
}
