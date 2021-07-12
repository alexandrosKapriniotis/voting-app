<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }

    /**
     * @return hasMany
     */
    public function votes(): hasMany
    {
        return $this->hasMany(Idea::class,'votes');
    }

    public function getAvatar()
    {
        $firstCharacter = $this->email[0];

        if(is_numeric($firstCharacter))
        {
            $integerToUse = ord(strtolower($firstCharacter)) - 21;
        } else {
            $integerToUse = ord(strtolower($firstCharacter)) - 96;
        }

        $avatarDefaults = ['mp', 'identicon', 'monsterid', 'retro', 'robohash'];
        $randomInteger = rand(0, count($avatarDefaults) - 1);

        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200'.'&d=s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-'.$integerToUse.'.png';
    }
}
