<?php

/**
 * Contributeur d'un film
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{ HasOne, BelongsTo };

/**
 * @property ?int $id
 * @property int $movie_id Identifiant du film
 * @property int $person_id Identifiant de la personne
 * @property MovieContributorTypeEnum $contributor_type Type de contributeur
 *
 * @property ?Movie $movie Film
 * @property ?Person $person Personne qui a contribué
 */
final class MovieContributor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movie_contributors';

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
        'movie_id',
        'person_id',
        'contributor_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'movie_id' => 'integer',
        'person_id' => 'integer',
        'contributor_type' => MovieContributorTypeEnum::class,
    ];

    /**
     * Retourne la relation avec le film
     * @return BelongsTo
     */
    public function movie() : BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

    /**
     * Retourne la relation avec la personne
     * @return HasOne
     */
    public function person() : HasOne
    {
        return $this->hasOne(Person::class, 'id', 'person_id');
    }
}
