<?php

/**
 * Film
 */

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property ?int $id
 * @property string $public_id Identifiant public
 * @property string $name Nom
 * @property ?CarbonImmutable $produced_at
 *
 * @property Collection<int, MovieContributor> $contributors
 */
final class Movie extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movies';

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
        'produced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'produced_at' => 'immutable_datetime:Y-m-d',
    ];

    /**
     * Retourne la relation avec les contributeurs du film
     * @return HasMany
     */
    public function contributors() : HasMany
    {
        return $this->hasMany(MovieContributor::class, 'movie_id', 'id');
    }

}
