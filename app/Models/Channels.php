<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Channels",
 *     description="Canais de comunicação que um usuário conhecido pela API pode se comunicar, ex. Telegram.",
 *     @OA\Xml(
 *         name="Channels"
 *     )
 * )
 */
class Channels extends Model
{
    protected $fillable = [
        'user_id',
        'fcm_token',
        'telegram_id'
    ];

    /**
     * Owner of this set of channels
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
