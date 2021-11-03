<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Chat",
 *     description="Chat model",
 *     @OA\Xml(
 *         name="Chat"
 *     )
 * )
 */
class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'group',
        'name',
        'description',
    ]; 
    
    /**
     * List of messages in this chat
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * The users that belong to the chat.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }    
}
