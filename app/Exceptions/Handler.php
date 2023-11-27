<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

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
    }

    /**
     * Обработчик ошибок
     *
     * @param $request
     * @param Exception $exception
     * @return mixed
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        $response = [];
        $response['message'] = $exception->getMessage();
        $status = $exception->status ?? 500;
        if ($exception instanceof AuthenticationException) {
            $status = 401;
        } elseif ($exception instanceof NotFoundHttpException) {
            $status = 404;
            $response['message'] = 'Not Found';
        } elseif ($status === 422) {
            $status = 400;
            $response['errors'] = $exception->errors();
        }
        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
        }

        return response()->json($response, $status);
    }
}
