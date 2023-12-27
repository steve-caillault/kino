<?php

/**
 * Classe de débogage
 */

namespace App;

use Illuminate\Support\Facades\{ DB, Log };
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Database\Events\QueryExecuted;
/***/
use App\Models\Log as LogModel;

final class Debug {

    /**
     * Channel des logs à utiliser
     * @var string
     */
    private string $log_channel = 'database_query';

    /**
     * Vrai si les logs des requêtes sont déjà actifs
     * @var bool
     */
    private bool $enabledQueryLog = false;

    /**
     * Requêtes SQL exécutées
     * @var array
     */
    private array $queries = [];

    /**
     * Constructeur
     * @param ?Request $request
     * @apram ?Command $command
     */
    public function __construct(
        private readonly ?Request $request = null,
        private readonly ?Command $command = null
    )
    {

    }

    /*******************************************************************/

    /**
     * Retourne si les logs des requêtes peuvent être écrit
     * @return bool
     */
    public function canWriteQueryLog() : bool
    {
        return config('logging.channels.database_query.enabled', false);
    }

    /**
     * Retourne la dernière requête SQL exécutée
     * @return ?string
     */
    public function getLastQuery() : ?string
    {
        return collect($this->queries)->last();
    }

    /**
     * Retourne les requêtes SQL exécutées
     * @return array
     */
    public function getQueries() : array
    {
        return $this->queries;
    }

    /**
     * Modifie la chaine des logs à utiliser
     * @param string $channel (voir dans le fichier config/logging)
     * @return self
     */
    public function setChannelLog(string $channel) : self
    {
        $this->log_channel = $channel;
        return $this;
    }

    /*******************************************************************/

    /**
     * Active le débogage des requêtes
     * @return void
     */
    public function enableQueryLog(bool $canWrite = false) : void
    {
        if($this->enabledQueryLog)
        {
            return;
        }

        DB::enableQueryLog();

        $this->enabledQueryLog = true;

        if(! $canWrite)
        {
            return;
        }

        DB::listen(function (QueryExecuted $query) {

            $bindings = collect($query->bindings)->map(function($binding) {
                if($binding instanceof \DateTimeInterface) {
                    return $binding->format('\'Y-m-d H:i:s\'');
                }
                return $binding;
            })->all();

            $queryString = $query->connection->getQueryGrammar()->substituteBindingsIntoRawSql($query->sql, $bindings);
            $this->addQueryLog($queryString);
        });
    }

    /**
     * Ajoute le message de log de la requête en paramètre
     * @param string $query
     * @return void
     */
    private function addQueryLog(string $query) : void
    {
        // Pour éviter un enregistrement des requêtes en boucle
        // À cause des insertions dans la table des logs
        $tableLog = (new LogModel())->getTable();
        if(str_contains($query, $tableLog))
        {
            return;
        }

        $message = $this->getLogQueryMessage($query);

        // Vérifie si on exclut la route des logs
        $routeName = $this->request?->route()?->getName();
        $excludesRoutesInlined = (env('LOG_DATABASE_QUERY_EXCLUDED_ROUTES') ?: null);
        $excludesRoutes = ($excludesRoutesInlined !== null) ? explode(',', $excludesRoutesInlined) : [];
        if($routeName !== null and in_array($routeName, $excludesRoutes))
        {
            return;
        }

        Log::channel($this->log_channel)->debug($message);

        $this->queries[] = $query;
    }

    /**
     * Retourne le message de log de la requête en paramètre
     * @param string $query
     * @return string
     */
    private function getLogQueryMessage(string $query) : string
    {
        $prefix = $this->getMessagePrefix();

        $data = collect([
            $prefix,
            $query
        ])  ->filter(fn(?string $value) => $value !== null)
            ->values()
            ->all();

        return implode(' - ', $data);
    }

    /**
     * Retourne le préfixe du message du log : soit le nom de la requête HTTP, soit celui du nom de la commande (CLI)
     * @return ?string
     */
    private function getMessagePrefix() : ?string
    {
        $routeName = $this->request?->route()?->getName();
        $commandName = $this->command?->getName();
        return ($routeName ?: $commandName);
    }

    /*******************************************************************/

}
