<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;

/**
 * @OA\Schema(
 *     title="Scraper",
 *     description="Coletor (scraper) de conteúdo para um usuário em particular. Coletores buscam dados de fontes externas, como o portal do Aluno da UFFS.",
 *     @OA\Xml(
 *         name="Scraper"
 *     )
 * )
 */
class Scraper extends Model
{
    use HasFactory;
    use Notifiable;
    use EncryptableDbAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'target',
        'access_user',
        'access_password'
    ];

    /**
     * The attributes that should be encrypted/decrypted to/from db.
     */
    protected $encryptable = [
        'access_user',
        'access_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'access_user',
        'access_password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Owner of this scraper.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
