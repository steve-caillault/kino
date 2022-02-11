<?php

/**
 * Utilisateur
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\{
    Hash,
    Mail
};
use Illuminate\Contracts\Auth\CanResetPassword;
/***/
use App\Mail\ResetPasswordMail;

/**
 * @property string $password Mot de passe crypté
 */
class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset() : string
    {
        return $this->email;
    }

    /**
     * Retourne l'URI de réinitialisation de mot de passe
     * @param string $token
     * @return string
     */
    public function getResetPasswordUri(string $token) : string
    {
        return ''; // A surcharger dans les classes filles
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification(/*string*/ $token) /*: void*/
    {
        $mail = new ResetPasswordMail(
            user: $this, 
            token: $token
        );

        Mail::mailer()->send($mail);
    }

    /**
     * Retourne le nom complet
     * @return string
     */
    public function getFullNameAttribute() : string
    {
        return trim(implode(' ', [
            $this->first_name,
            $this->last_name,
        ])) ?: $this->nickname;
    }

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
    public function setPermissionsAttribute(array $permissions) : self
    {
        $this->attributes['permissions'] = json_encode(array_map('strtoupper', $permissions));
        return $this;
    }

    /**
     * Retourne les permissions autorisées
     * @return array
     */
    public static function permissionsAllowed() : array
    {
        return array_column(Permission::cases(), 'name');
    }
    
    /**
     * Retourne si l'utilisateur est administrateur
     * @return bool
     */
    public function isAdministrator() : bool
    {
        return $this->permissions->collect()->contains(Permission::ADMIN->name);
    }

}
