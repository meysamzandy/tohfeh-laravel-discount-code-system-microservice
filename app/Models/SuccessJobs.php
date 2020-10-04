<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessJobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'resultStats','body','message','statusCode'
    ];
}