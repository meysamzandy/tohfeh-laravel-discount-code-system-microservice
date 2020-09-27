<?php

namespace App\Models;

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
}
