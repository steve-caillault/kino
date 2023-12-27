<?php

/**
 * Modèle d'un log en base de données
 */

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Monolog\Level;

/**
 * @property ?int $id
 * @property ?CarbonImmutable $created_at
 * @property ?string $path Chemin de la requête ou commande de la console
 * @property Level $level Niveau d'urgence
 * @property string $message
 * @property ?string $user_agent
 */
final class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
        'level',
        'message',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'immutable_datetime',
        'level' => Level::class,
    ];

    /**
     * Get the name of the "updated at" column.
     *
     * @return string|null
     */
    public function getUpdatedAtColumn() : ?string
    {
        return null;
    }
}
