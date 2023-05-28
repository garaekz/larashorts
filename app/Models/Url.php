<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'url',
        'code',
        'original_url',
        'urlable_type',
        'urlable_id',
    ];

    public function urlable()
    {
        return $this->morphTo();
    }
}
