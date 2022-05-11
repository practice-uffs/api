<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'key',
        'value',
        'rate',
        'app_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
