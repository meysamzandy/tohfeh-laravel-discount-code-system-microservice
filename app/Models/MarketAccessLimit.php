<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketAccessLimit extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_id','market_name','version_major','version_minor','version_patch'
    ];
}
