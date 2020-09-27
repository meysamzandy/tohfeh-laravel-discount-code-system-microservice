<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id','code','created_type','access_type','usage_limit','usage_count','usage_limit_per_user','first_buy','has_market','cancel_date'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DiscountCodeGroups::class, 'group_id','id');
    }
}
