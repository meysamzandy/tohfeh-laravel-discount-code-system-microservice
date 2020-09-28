<?php

namespace App\Models;

//use App\Http\Helper\SmallHelper;
use App\Http\Helper\SmallHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;


class DiscountCode extends Model
{
    use HasFactory;

    public const RESULT_STATS = 'resultStats';
    public const STATUS_CODE = 'statusCode';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public $smallHelper;

    protected $fillable = [
        'group_id', 'code', 'created_type', 'access_type', 'usage_limit', 'usage_count', 'usage_limit_per_user', 'first_buy', 'has_market', 'cancel_date'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DiscountCodeGroups::class, 'group_id', 'id');
    }

    /**
     * @param $data
     * @return array|null
     */
    public function createCode($data): ?array
    {

        if ($data['created_type'] === 'auto') {

            [$groupData, $featuresData, $CodeData, $marketData] = SmallHelper::prepareDataForAutoCodes($data);
            DB::beginTransaction();
            try {

                $group = DiscountCodeGroups::create($groupData);

                foreach ($featuresData as $feature) {

                    $feature['group_id'] = $group->id;
                    DiscountCodeFeatures::create($feature);

                }

                DB::commit();

                $stringType = [
                    0 => config('settings.generatorString.number'),
                    1 => config('settings.generatorString.alphabetic'),
                    2 => config('settings.generatorString.bothCharacter'),
                ];

                $count = 0;
                for ($i = 1; $i <= $data['creation_code_count']; $i++) {

                    $generateCode = SmallHelper::codeGenerator($data['prefix'], $stringType[$data['stringType']], config('settings.automateCodeLength'));

                    if (!$generateCode) {
                        continue;
                    }

                    $CodeData['group_id'] = $group->id;
                    $CodeData['code'] = $generateCode;


                    DB::beginTransaction();
                    try {
                        $code = self::create($CodeData);
                        foreach ($marketData as $market) {
                            $market['code_id'] = $code->id;
                            MarketAccessLimit::create($market);
                        }
                        DB::commit();
                        $count++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        return [
                            self::RESULT_STATS => false,
                            self::STATUS_CODE => 417,
                            self::BODY => null,
                            self::MESSAGE => $e->getMessage()
                        ];
                    }

                }

                return [
                    self::RESULT_STATS => true,
                    self::STATUS_CODE => 201,
                    self::BODY => trans('messages.countOfCodeCreation',[ 'count' => $count ]),
                    self::MESSAGE => null
                ];
            } catch (\Exception $e) {
                DB::rollback();
                return [
                    self::RESULT_STATS => false,
                    self::STATUS_CODE => 417,
                    self::BODY => null,
                    self::MESSAGE => $e->getMessage()
                ];
            }


        }

        if ($data['created_type'] === 'manual') {
            [$groupData, $featuresData, $CodeData, $userListData, $marketData] = SmallHelper::prepareDataForManualCodes($data);
            $isCodeExist = self::query()->where('code', $CodeData['code'])->exists();
            if ($isCodeExist) {

                return [
                    self::RESULT_STATS => false,
                    self::STATUS_CODE => 403,
                    self::BODY => null,
                    self::MESSAGE => __('messages.CodeExist')
                ];
            }

            DB::beginTransaction();
            try {

                $group = DiscountCodeGroups::create($groupData);


                foreach ($featuresData as $feature) {
                    $feature['group_id'] = $group->id;
                    DiscountCodeFeatures::create($feature);
                }


                $CodeData['group_id'] = $group->id;
                $code = self::create($CodeData);

                if ($CodeData['has_market'] === true) {

                    foreach ($marketData as $market) {
                        $market['code_id'] = $code->id;
                        MarketAccessLimit::create($market);
                    }

                }

                if ($CodeData['access_type'] === 'private') {

                    foreach ($userListData as $uuid) {

                        UserAccessLimit::create(['code_id' => $code->id, 'uuid' => $uuid]);

                    }

                }
                DB::commit();
                return [
                    self::RESULT_STATS => true,
                    self::STATUS_CODE => 201,
                    self::BODY =>  trans('messages.countOfCodeCreation',[ 'count' => 1 ]),
                    self::MESSAGE => null,
                ];
            } catch (\Exception $e) {
                DB::rollback();
                return [
                    self::RESULT_STATS => false,
                    self::STATUS_CODE => 417,
                    self::BODY => null,
                    self::MESSAGE => $e->getMessage()
                ];
            }

        }
        return [
            self::RESULT_STATS => false,
            self::STATUS_CODE => 417,
            self::BODY => null,
            self::MESSAGE => __('messages.exceptionError')
        ];

    }
}
