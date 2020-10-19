<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SuccessJobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'resultStats','body','message','statusCode'
    ];

    /**
     * @return array|string[]
     */
    public function getParams(): array
    {
        return Schema::getColumnListing('discount_code_groups');
    }
}