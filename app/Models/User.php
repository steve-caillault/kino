<?php

/**
 * Utilisateur
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

/**
 * @property string $password Mot de passe crypté
 */
final class User extends Authenticatable
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'first_name',
        'last_name',
        'password',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => AsArrayObject::class,
    ];

    /**
     * Modification du mot de passe
     * @param string $password
     * @return self
     */
    public function setPasswordAttribute(string $password) : self
    {
        $this->attributes['password'] = Hash::make($password);
        return $this;
    }

    /**
     * Modification des permissions
     * @param array $permissions
     * @return self
     */
    public function setPermissionsAttribute(array $permssions) : self
    {
        $this->attributes['permissions'] = json_encode(array_map('strtoupper', $permssions));
        return $this;
    }

    /**
     * Retourne les permissions autorisées
     * @return array
     */
    public static function permissionsAllowed() : array
    {
        return [ 
            Permission::ADMIN->name,
        ];
    }
}
