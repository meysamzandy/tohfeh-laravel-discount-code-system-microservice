<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessLimit extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_id','uuid'
    ];
}
