<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
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
}
