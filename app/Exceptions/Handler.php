<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Leo108\CAS\Exceptions\CAS\CasException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ValidatesRequests;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        UserException::class,
        CasException::class,
        BindOauthFailedException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof UserException) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            } else {
                //todo render error page
            }
        }

        if ($e instanceof ValidationException && !$e->getResponse()) {
            $e->response = $this->buildFailedValidationResponse($request, $this->formatValidationErrors($e->validator));
        }

        return parent::render($request, $e);
    }
}
