<?php

/**
 * Gestion des exceptions
 */

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\{ Request };
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class Handler extends ExceptionHandler
{

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $e) : void
    {
        // Log en cas de message d'erreur vide
        if($e->getMessage() === '' and ! $this->shouldntReport($e))
        {
            $message = $this->getDisplayedMessage($e);
            Log::error($message);
            return;
        }

        parent::report($e);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param Throwable $e
     * @return bool
     */
    protected function shouldntReport(Throwable $e) : bool
    {
        // Nous souhaitons que ces erreurs soient journalisées
        $canReport = [
            HttpException::class,
            HttpResponseException::class,
        ];

        $this->internalDontReport = collect($this->internalDontReport)
            ->filter(fn($class) => ! in_array($class, $canReport))
            ->toArray()
        ;

        return parent::shouldntReport($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Jeton CSRF expiré
        $this->renderable(function(TokenMismatchException $e, Request $request) {
            if($request->ajax())
    		{
    			return response()->json([
    				'errorCSRF' => true,
    			])->setStatusCode(500);
    		}
    		else
    		{
                // Recharge la page courante
                $currentUrl = $request->fullUrlWithQuery([
                    'reason' => 'csrfTokenExpired',
                ]);
    			return redirect($currentUrl);
    		}
        });
    }

    /**
     * Retourne le message d'erreur à afficher
     * @param Throwable $exception
     * @return string
     */
    private function getDisplayedMessage(Throwable $exception) : string
    {
        $code = $this->getStatusCode($exception) ?: 500;

        $displayedMessage = $exception->getMessage();

        if($displayedMessage === '' or $code === 404)
    	{
            $displayedMessage = match($code) {
                401 => trans('error.authenticate'),
                403 => trans('error.forbidden'),
                404 => trans('error.not_found'),
                default => trans('error.default')
            };
    	}

        return $displayedMessage;
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     * @return \Throwable
     */
    protected function prepareException(Throwable $e) : Throwable
    {
        return match (true) {
            $e instanceof TokenMismatchException => $e,
            default => parent::prepareException($e),
        };
    }

    /**
     * Render a default exception response if any.
     *
     * @param Request $request
     * @param Throwable  $e
     * @return Response
     */
    protected function renderExceptionResponse(/*Request*/ $request, Throwable $exception) : Response
    {
        if(app()->environment('local'))
        {
            return parent::renderExceptionResponse($request, $exception);
        }

        $code = $this->getStatusCode($exception) ?: 500;
        $displayedMessage = $this->getDisplayedMessage($exception);

        $allowedCodes = [401, 403, 404, 500];
    	if(! in_array($code, $allowedCodes) or $code === 500)
    	{
    		$code = 500;
    		$displayedMessage = trans('error.default');
    	}

        if($request->ajax())
        {
            return response()->json([
                'error' => [
                    'status' => $code,
                    'message' => $displayedMessage,
                ],
            ]);
        }

        try {
            $content = view('pages.error', [
                'code' => $code,
                'message' => $displayedMessage,
            ]);
            return response($content, $code);
        } catch(Throwable $fatalException) {
            Log::critical($fatalException->getMessage());
            return response(trans('error.default'));
        }
    }

    /**
     * Retourne le code HTTP de l'exception 
     * @param \Throwable $exception
     * @return ?int
     */
    private function getStatusCode(Throwable $exception) : ?int
    {
        $code = (method_exists($exception, 'getCode')) ? ($exception->getCode() ?: null) : null;
        if($code === null)
        {
            $code = method_exists($exception, 'getStatusCode') ? ($exception->getStatusCode() ?: null) : null;
        }

        return $code;
    }
}
