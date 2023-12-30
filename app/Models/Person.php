<?php

/**
 * Personne
 */

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property ?int $id
 * @property string $first_name Prénom
 * @property string $last_name Nom
 * @property string $full_name
 * @property ?CarbonImmutable $birthdate
 */
final class Person extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';

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
        'first_name',
        'last_name',
        'birthdate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birthdate' => 'immutable_datetime:Y-m-d',
    ];

    /**
     * Retourne le nom complet
     * @return string
     */
    public function getFullNameAttribute() : string
    {
        return trim(implode(' ', [
            $this->first_name,
            $this->last_name,
        ]));
    }

    /**
     * Modifie le prénom avec synchronisation du nom complet
     * @param string $firstName
     * @return void
     */
    public function setFirstNameAttribute(string $firstName) : void
    {
        $this->attributes['first_name'] = $firstName;
        $this->attributes['full_name'] = $this->full_name;
    }

    /**
     * Modifie le nom avec synchronisation du nom complet
     * @param string $lastName
     * @return void
     */
    public function setLastNameAttribute(string $lastName) : void
    {
        $this->attributes['last_name'] = $lastName;
        $this->attributes['full_name'] = $this->full_name;
    }


}
