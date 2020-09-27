<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_id','code','uuid'
    ];


    /**
     * @param $code_id
     * @param $code
     * @param $uuid
     * @return Builder|Model|null
     */
    public function insertUsageLog($code_id, $code, $uuid)
    {
        try {
            return self::query()->create([
                'code_id' => $code_id,
                'code' => $code,
                'uuid' => $uuid
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param string $code
     * @param null $uuid
     * @return int
     */
    public function countUsageLog(string $code, $uuid = null): int
    {
        try {
            $usage = self::query()->where([
                'uuid' => $uuid,
                'code' => $code,
            ])->count();
        } catch (\Exception $e) {
            $usage = 0;
        }
        return $usage;
    }
}
