<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','series'
    ];
}
