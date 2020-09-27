<?php

namespace App\Models;

use App\Http\Helper\SmallHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCodeFeatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id','plan_id','start_time','end_time','code_type','percent','limit_percent_price','price','description'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DiscountCodeGroups::class, 'group_id','id');
    }


    public function createFeatures(int $group_id, array $features): ?bool
    {
        $smallHelper = new SmallHelper();
            $existingFeatures = self::query()->where('group_id', $group_id)->get()->all() ;
            $count = count($existingFeatures) - 1 ;
            $checkinArray = $existingFeatures;

            for ($i = 0; $i < $count ; $i++) {
                foreach ($checkinArray as $key => $value) {

                        $IntervalStart_timeStatus = $smallHelper->checkDateInterval($features[$i]['start_time'], $value['start_time'], $value['end_time']);
                        $IntervalEnd_timeStatus = $smallHelper->checkDateInterval($features[$i]['end_time'], $value['start_time'], $value['end_time']);
                        if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalStart_timeStatus) {
                            return false ;
                        }
                        if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalEnd_timeStatus) {
                            return false ;
                        }

                    $feature['group_id'] = $group_id;
                    self::create($feature);
                }
            }
            return true ;
    }
}
