<?php

namespace App\Models;

use App\Http\Helper\SmallHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DiscountCodeFeatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'plan_id', 'start_time', 'end_time', 'code_type', 'percent', 'limit_percent_price', 'price', 'description'
    ];

    /**
     * @return BelongsTo|null
     */
    public function group(): ?BelongsTo
    {
        try {
            return $this->belongsTo(DiscountCodeGroups::class, 'group_id', 'id');
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param $featuresData
     * @param $group_id
     * @return bool
     */
    public function createFeature($featuresData, $group_id): bool
    {
        try {
            foreach ($featuresData as $feature) {

                $feature['group_id'] = $group_id;
                (new self($feature))->save();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * @param int $group_id
     * @param array $features
     * @return bool
     */
    public function checkFeatureBeforeAddToExistingCode(int $group_id, array $features): bool
    {
        $smallHelper = new SmallHelper();
        $existingFeatures = self::query()->where('group_id', $group_id)->get()->all();
        $count = count($existingFeatures);
        $checkinArray = $existingFeatures;
        for ($i = 0; $i < $count; $i++) {
            foreach ($checkinArray as $key => $value) {
                $IntervalStart_timeStatus = $smallHelper->checkDateInterval($features[$i]['start_time'], $value['start_time'], $value['end_time']);
                $IntervalEnd_timeStatus = $smallHelper->checkDateInterval($features[$i]['end_time'], $value['start_time'], $value['end_time']);
                if (((int)$features[$i]['plan_id'] === (int)$value['plan_id']) && $IntervalStart_timeStatus) {
                    return false;
                }
                if (((int)$features[$i]['plan_id'] === (int)$value['plan_id']) && $IntervalEnd_timeStatus) {
                    return false;
                }
            }
        }
        return true;
    }


    /**
     * @param int $group_id
     * @param array $features
     * @return array
     */
    public function addFeaturesToExistingCode(int $group_id, array $features): array
    {
        DB::beginTransaction();
        try {
            foreach ($features as $feature) {
                $feature['group_id'] = $group_id;
                self::create($feature);
            }
            DB::commit();
            return [
                'status' => true,
                'statusCode' => 201,
                'message' => null
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'status' => false,
                'statusCode' => 417,
                'message' => $e->getMessage()
            ];
        }

    }


    /**
     * @param  $features
     * @return array
     */
    public function processFeatures($features): array
    {
        $discountData = [];
        foreach ($features as $feature) {
            // if feature doesn't start yet
            if (now() < $feature['start_time']) {
                $discountData [$feature['plan_id']][] = [
                    'feature_status' => false,
                    'plan_id' => $feature['plan_id'],
                    'start_time' => $feature['start_time'],
                    'end_time' => $feature['end_time'],
                    'message' => __('messages.left_to_start')
                ];
                continue;
            }
            // if feature expired
            if (now() > $feature['end_time']) {
                $discountData [$feature['plan_id']][] = [
                    'feature_status' => false,
                    'plan_id' => $feature['plan_id'],
                    'start_time' => $feature['start_time'],
                    'end_time' => $feature['end_time'],
                    'message' => __('messages.past_from_end')
                ];
                continue;
            }
            // if feature type is percent
            if ($feature['code_type'] === 'percent') {
                $discountData [$feature['plan_id']][] = [
                    'feature_status' => true,
                    'type' => $feature['code_type'],
                    'plan_id' => $feature['plan_id'],
                    'percent' => $feature['percent'],
                    'limit_price' => $feature['limit_percent_price'],
                    'description' => $feature['description'],
                ];
                continue;
            }
            // if feature type is price
            if ($feature['code_type'] === 'price') {
                $discountData [$feature['plan_id']][] = [
                    'feature_status' => true,
                    'type' => $feature['code_type'],
                    'plan_id' => $feature['plan_id'],
                    'price' => $feature['price'],
                    'description' => $feature['description'],
                ];
                continue;
            }
            // if feature type is free
            if ($feature['code_type'] === 'free') {
                $discountData [$feature['plan_id']][] = [
                    'feature_status' => true,
                    'type' => $feature['code_type'],
                    'plan_id' => $feature['plan_id'],
                    'description' => $feature['description'],
                ];
                continue;
            }
        }
        return $discountData;
    }


    /**
     * @param $features
     * @return array
     */
    public function prepareFeaturesToResponse($features): array
    {

        $data = [];
        $discountData = $this->processFeatures($features);
        foreach ($discountData as $value) {
            if (count($value) > 1) {
                foreach ($value as $item) {
                    if ($item['feature_status'] === true) {
                        $data [] = $item;
                    }
                }
                    // if there is no eny true feature_status
                if (count($data) <= 0) {
                    $data [] =end($value);
                }
                continue;
            }
            $data [] = $value[0];
        }
        return $data;
    }
}
