<?php

/**
 * Film
 */

namespace App\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $public_id Identifiant public
 * @property string $name Nom
 * @property DateTimeImmutable $production_date
 */
final class Movie extends Model
{
    use HasFactory;

    /**self
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
        'production_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'production_date' => 'datetime:Y-m-d',
    ];

     /**
     * Modification des permissions
     * @param string $date
     * @return DateTimeImmutable
     */
    public function getProductionDateAttribute(string $date) : DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }

}
