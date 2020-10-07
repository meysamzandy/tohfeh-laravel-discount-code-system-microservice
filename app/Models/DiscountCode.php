<?php

namespace App\Models;

use App\Http\Helper\SmallHelper;
use App\Jobs\ProcessAutoCodeCreation;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;


class DiscountCode extends Model
{
    use HasFactory;

    public const RESULT_STATS = 'resultStats';
    public const STATUS_CODE = 'statusCode';
    public const BODY = 'body';
    public const MESSAGE = 'message';

    protected $fillable = [
        'group_id', 'code', 'created_type', 'access_type', 'usage_limit', 'usage_count', 'usage_limit_per_user', 'first_buy', 'has_market', 'cancel_date'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DiscountCodeGroups::class, 'group_id', 'id');
    }

    public function markets(): HasMany
    {
        return $this->hasMany(MarketAccessLimit::class,'code_id' , 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserAccessLimit::class,'code_id' , 'id');
    }
    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class,'code_id' , 'id');
    }

    /**
     * @param $data
     * @return array|null
     */
    public function createCode($data): ?array
    {

        if ($data['created_type'] === 'auto') {
            return $this->insertAutoCode($data);
        }

        if ($data['created_type'] === 'manual') {
            return $this->insertManualCode($data);
        }

        return SmallHelper::returnStatus(false, 417,null,__('messages.exceptionError'));

    }

    /**
     * @param $data
     * @return array
     */
    public function insertAutoCode($data): array
    {
        // prepare data for auto code
        [$groupData, $featuresData, $CodeData, $marketData] = SmallHelper::prepareDataForAutoCodes($data);
        DB::beginTransaction();
        try {

            //  create group
            $group = new DiscountCodeGroups($groupData);
            $group->save();

            //  create feature
            (new DiscountCodeFeatures)->createFeature($featuresData, $group['id']);

            DB::commit();

            // get String type from config
            $stringType = [
                0 => config('settings.generatorString.number'),
                1 => config('settings.generatorString.alphabetic'),
                2 => config('settings.generatorString.bothCharacter'),
            ];

            // loop for create auto code for creation code count
            $count = 0;
            for ($i = 1; $i <= $data['creation_code_count']; $i++) {

                //generate a code
                $generateCode = SmallHelper::codeGenerator($data['prefix'], $stringType[$data['stringType']], config('settings.automateCodeLength'));

                if (!$generateCode) {
                    continue;
                }

                DB::beginTransaction();
                try {
                    $CodeData['group_id'] = $group['id'];
                    $CodeData['code'] = $generateCode;
                    //create a code in db
                    $code = new self($CodeData);
                    $code->save();

                    // create market associated with code if has_market is true
                    (new MarketAccessLimit)->createMarket($CodeData['has_market'], $marketData, $code['id']);

                    DB::commit();
                    $count++;
                } catch (Exception $e) {
                    DB::rollback();
                    return SmallHelper::returnStatus(false, 417,null,$e->getMessage());
                }

            }

            return SmallHelper::returnStatus(true, 201,trans('messages.countOfCodeCreation', ['count' => $count]));

        } catch (Exception $e) {
            DB::rollback();
            return SmallHelper::returnStatus(false, 417,null,$e->getMessage());
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function insertManualCode($data): array
    {
        // prepare data for manual code
        [$groupData, $featuresData, $CodeData, $userListData, $marketData] = SmallHelper::prepareDataForManualCodes($data);

        // check if code not exist in db
        $isCodeExist = self::query()->where('code', $CodeData['code'])->exists();

        if ($isCodeExist) {
            return SmallHelper::returnStatus(false, 417,null,__('messages.CodeExist'));
        }

        DB::beginTransaction();
        try {

            //  create group
            $group = new DiscountCodeGroups($groupData);
            $group->save();

            //  create feature
           (new DiscountCodeFeatures)->createFeature($featuresData, $group['id']);

            //  create code
            $CodeData['group_id'] = $group['id'];

            $code = new self($CodeData);
            $code->save();

            // create market associated with code if has_market is true
            (new MarketAccessLimit)->createMarket($CodeData['has_market'], $marketData, $code['id']);

            // create user associated with code access_type is private
            (new UserAccessLimit)->createUserAccess($CodeData['access_type'], $userListData, $code['id']);

            DB::commit();

            return SmallHelper::returnStatus(true, 201,trans('messages.countOfCodeCreation', ['count' => 1]));

        } catch (Exception $e) {
            DB::rollback();
            return SmallHelper::returnStatus(false, 417,null,$e->getMessage());
        }
    }



}
