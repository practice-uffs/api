<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="App",
 *     description="Uma aplicação que pode interagir com a API ou ser acessada a partir dela.",
 *     @OA\Xml(
 *         name="App"
 *     )
 * )
 */
class App extends Model
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
        'secret',
        'api_url',
        'slug',
        'name',
        'description',
        'domain'
    ];

    /**
     * The attributes that should be encrypted/decrypted to/from db.
     */
    protected $encryptable = [
        'secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    /**
     * 
     */
    public function getApiUrlAttribute($value) {
        if (substr($value, -1) !== '/') {
            $value .= '/';
        }
        
        return $value;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
