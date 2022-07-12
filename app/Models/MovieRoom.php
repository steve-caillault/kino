<?php

/**
 * Salle de cinéma
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $public_id Identifiant public
 * @property string $name Nom
 * @property int $floor Etage
 * @property int $nb_places Nombre total de places
 * @property int $nb_handicap_places Nombre de places adaptées pour un handicap
 */
final class MovieRoom extends Model
{
    use HasFactory, WithPublicIdTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movie_rooms';

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
        'public_id',
        'name',
        'floor',
        'nb_places',
        'nb_handicap_places',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'floor' => 'integer',
        'nb_places' => 'integer',
        'nb_handicap_places' => 'integer',
    ];

}
