<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Application extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'user_id',
    ];


    /**
     * Get all of the URLs for the application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function urls()
    {
        return $this->morphMany(Url::class, 'urlable');
    }

    /**
     * Get the user that owns the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the API keys for the application.
     */
    public function apikeys()
    {
        return $this->hasMany(Apikey::class);
    }
}
