<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketAccessLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_id','market_name','version_major','version_minor','version_patch'
    ];

    public function code(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class, 'code_id', 'id');
    }


    /**
     * @param $CodeData
     * @param $marketData
     * @param $code_id
     */
    public function createMarket($CodeData, $marketData, $code_id): void
    {
        if ($CodeData === true) {

            foreach ($marketData as $market) {
                $market['code_id'] = $code_id;
                (new self($market))->save();
            }

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
            return self::query()->where([
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
