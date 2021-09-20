<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'jwt_signature',
        'is_valid'
    ];

    /**
     * Obtem os parâmetros informados na URL de checkin.
     */
    public function getParamsAttribute() {
        $url = $this->attributes['url'];
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $queryParams);
        
        return $queryParams;
    }

    /**
     * Owner of this checkin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}