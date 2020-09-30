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
     * @param $CodeData
     * @param $userListData
     * @param $code_id
     */
    public function createUserAccess($CodeData, $userListData, $code_id): void
    {
        if ($CodeData === 'private') {

            foreach ($userListData as $uuid) {

                (new self(['code_id' => $code_id, 'uuid' => $uuid]))->save();

            }
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
