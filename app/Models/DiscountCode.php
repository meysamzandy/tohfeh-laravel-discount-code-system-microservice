<?php

namespace App\Models;

//use App\Http\Helper\SmallHelper;
use App\Http\Helper\SmallHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DiscountCode extends Model
{
    use HasFactory;

    public $smallHelper;

    protected $fillable = [
        'group_id', 'code', 'created_type', 'access_type', 'usage_limit', 'usage_count', 'usage_limit_per_user', 'first_buy', 'has_market', 'cancel_date'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DiscountCodeGroups::class, 'group_id', 'id');
    }

    public function createCode($data)
    {
        $this->smallHelper = new SmallHelper();
        $smallHelper = new SmallHelper();
        if ($smallHelper->checkIfFeatureCouldInsert($data['features'])) {
            
            if ($data['created_type'] === 'auto') {
                $group_id = DiscountCodeGroups::create([
                    'group_name' => $data['group_name'],
                    'series' => $data['series'],
                ]);
                if ($group_id) {
                    $createFeaturesStatus = (new DiscountCodeFeatures)->createFeatures($group_id->id, $data['features']);
                    if ($createFeaturesStatus) {

                        $stringType = [
                            0 => config('settings.generatorString.number'),
                            1 => config('settings.generatorString.alphabetic'),
                            2 => config('settings.generatorString.bothCharacter'),
                        ];
                        for ($i = 1; $i <= $data['creation_code_count']; $i++) {

                            $generateCode = $this->smallHelper->codeGenerator($data['prefix'], $stringType[$data['stringType']], config('settings.automateCodeLength'));
                            if ($generateCode) {

                                $code_id = self::create([
                                    'group_id' => $group_id->id,
                                    'created_type' => $data['created_type'],
                                    'code' => $generateCode,
                                    'access_type' => $data['access_type'],
                                    'usage_limit' => $data['usage_limit'],
                                    'usage_limit_per_user' => $data['usage_limit_per_user'],
                                    'first_buy' => $data['first_buy'],
                                    'has_market' => $data['has_market'],
                                ]);
                                if ($code_id) {

                                    if ($data['has_market'] === true) {
                                        MarketAccessLimit::create([
                                            'code_id' => $code_id->id,
                                            'market_name' => $data['market_name'],
                                            'version_major' => $data['version_major'],
                                            'version_minor' => $data['version_minor'],
                                            'version_patch' => $data['version_patch'],
                                        ]);
                                    }
                                }


                            }
                        }
                    }
                }


            }
            if ($data['created_type'] === 'manual') {

                $isCodeExist = self::query()->where('code', $this->smallHelper->changeCodeToUppercase($data['code']))->exists();
                if (!$isCodeExist) {
                    $group_id = DiscountCodeGroups::create([
                        'group_name' => $data['group_name'],
                        'series' => $data['series'],
                    ]);
                    if ($group_id) {

                        $createFeaturesStatus = (new DiscountCodeFeatures)->createFeatures($group_id->id, $data['features']);
                        if ($createFeaturesStatus) {

                            $code_id = self::create([
                                'group_id' => $group_id->id,
                                'created_type' => $data['created_type'],
                                'code' => $this->smallHelper->changeCodeToUppercase($data['code']),
                                'access_type' => $data['access_type'],
                                'usage_limit' => $data['usage_limit'],
                                'usage_limit_per_user' => $data['usage_limit_per_user'],
                                'first_buy' => $data['first_buy'],
                                'has_market' => $data['has_market'],
                            ]);

                            if ($code_id) {

                                if ($data['has_market'] === true) {
                                    MarketAccessLimit::create([
                                        'code_id' => $code_id->id,
                                        'market_name' => $data['market_name'],
                                        'version_major' => $data['version_major'],
                                        'version_minor' => $data['version_minor'],
                                        'version_patch' => $data['version_patch'],
                                    ]);
                                }

                                if ($data['access_type'] === 'private') {

                                    foreach ($data['uuid_list'] as $uuid) {

                                        UserAccessLimit::create([
                                            'code_id' => $code_id->id,
                                            'uuid' => $uuid
                                        ]);

                                    }

                                }
                            }
                        }

                    }
                }

            }

        }




    }
}
