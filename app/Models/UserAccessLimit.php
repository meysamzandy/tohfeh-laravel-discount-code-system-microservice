<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessLimit extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_id','uuid'
    ];


    /**
     * @param int $code_id
     * @param $uuid
     * @return Builder|Model|null
     */
    public function insertUserAccessLimit(int $code_id, $uuid)
    {
        try {
            return self::query()->create([
                'code_id' => $code_id,
                'uuid' => $uuid
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $code_id
     * @param $uuid
     * @return bool
     */
    public function selectUserAccessLimit(int $code_id, $uuid): bool
    {
        try {
            return self::query()->where([
                'code_id' => $code_id,
                'uuid' => $uuid,
            ])->exists();
        } catch (Exception $e) {
            return false ;
        }
    }
}
