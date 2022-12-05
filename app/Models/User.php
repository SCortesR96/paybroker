<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cpf',
        'name',
        'email',
        'enabled',
        'deleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope);
    }

    public static function findByCPF($cpf)
    {
        return User::where('cpf', $cpf)->first();
    }

    /**
     * It returns a boolean value that verify if the user is enabled.
     *
     * @return bool A boolean value.
     */
    public function isEnabled(): bool
    {
        return $this->enabled ? true : false;
    }

    /**
     * It returns a boolean value that verify if the user is deleted.
     *
     * @return bool A boolean value.
     */
    public function isDeleted(): bool
    {
        return $this->deleted ? true : false;
    }
}
