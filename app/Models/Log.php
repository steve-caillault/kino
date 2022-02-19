<?php

/**
 * Modèle d'un log en base de données
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $created_at
 * @property ?string $path Chemin de la requête ou commande de la console
 * @property string $level Niveau d'urgence
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
        'created_at' => 'datetime:Y-m-d H:i:s',
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
