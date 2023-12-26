<?php

/**
 * Enregistrement de Log en base de données
 */

namespace App\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\{
    Logger,
    LogRecord
};
use Illuminate\Support\Facades\Log;
/***/
use App\Models\Log as LogModel;

final class DatabaseHandler extends AbstractProcessingHandler
{
	protected function write(LogRecord $record) : void
	{
		try {
			$request = request();

			$log = new LogModel([
				'level'	=> $record->level->getName(),
				'path' => $request?->path(),
				'message' => $record->message,
				'user_agent' => $request->userAgent(),
			]);
			
			$log->save();
			
		}
		catch(\Throwable $e) {
            // Log de l'erreur en fichier
            Log::channel('daily')->emergency($e->getMessage());
		}
	}
}
