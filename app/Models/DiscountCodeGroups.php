<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DiscountCodeFeatures;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class DiscountCodeGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name','series'
    ];

    /**
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(DiscountCodeFeatures::class,'group_id','id');
    }

    /**
     * @return HasMany
     */
    public function codes(): HasMany
    {
        return $this->hasMany(DiscountCode::class,'group_id','id');
    }

    /**
     * @return array|string[]
     */
    public function getParams(): array
    {
        return Schema::getColumnListing('discount_code_groups');
    }

}
