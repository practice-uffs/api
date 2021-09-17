<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $fillable = [
        'user_id',
        'fcm_token'
    ];

    /**
     * Owner of this set of channels
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}