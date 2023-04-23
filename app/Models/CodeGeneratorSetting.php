<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeGeneratorSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'max_length',
        'max_attempts',
        'max_retries',
    ];
}
