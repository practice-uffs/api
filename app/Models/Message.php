<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Message",
 *     description="Mensagem de um chat",
 *     @OA\Xml(
 *         name="Message"
 *     )
 * )
 */
class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chat_id',
        'user_id',
        'type',
        'content',
        'data'     
    ];

    /**
     * Chat this message belongs to.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * User this message belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
