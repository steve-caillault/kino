<?php

/**
 * Classe de débogage
 */

namespace App;

use Illuminate\Support\Facades\{ DB, Log };

final class Debug {
	
	/**
	 * Requêtes SQL exécutées
	 * @var array
	 */
	private array $queries = [];
	
	/*******************************************************************/
	
	/**
	 * Retourne la dernière requête SQL exécutée
	 * @return string
	 */
	public function getLastQuery() : ?string
	{
		$countQueries = count($this->queries);
		if($countQueries == 0)
		{
			return null;
		}
		
		return end($this->queries);
	}
	
	/**
	 * Retourne les requêtes SQL exécutées
	 * @return array
	 */
	public function getQueries() : array
	{
		return $this->queries;
	}
	
	/*******************************************************************/
	
	/**
	 * Active le débuggage des requêtes
	 * @return void
	 */
	public function enableQueryLog()
	{
		$localEnvironment = app()->environment('local');
		
		if(! $localEnvironment)
		{
			return;
		}
		
		DB::enableQueryLog();
		
		DB::listen(function ($sql) {
			foreach ($sql->bindings as $i => $binding) {
				if ($binding instanceof \DateTime) {
					$sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
				} else {
					if (is_string($binding)) {
						$sql->bindings[$i] = "'$binding'";
					}
				}
			}
			// Insert bindings into query
			$query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
			$query = vsprintf($query, $sql->bindings);
			
			Log::debug($query);
			$this->queries[] = $query;
		});
	}
	
	/*******************************************************************/
	
}
