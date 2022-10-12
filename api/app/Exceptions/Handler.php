<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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

    public function render($request, Throwable $e)
    {
        $result = [];
        $code = null;

        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $slug = $e->getIds();
            $type = $request->getMethod();
            $data = $model::where('id', $slug)->withTrashed()->first();
            $isDeleted = isset($data->deleted_at);

            if ($type == 'POST' && $isDeleted) {
                $data->restore();
                $result = $data;
                $code = 200;
            } else {
                $result = [
                    'status' => 'error',
                    'message' => 'No query results'
                ];
                $code = 200;
            }
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        if ($e instanceof NotFoundHttpException) {
            $result = [
                'status' => 'error',
                'message' => 'Requested route not found'
            ];
        }

        # Retorno geral
        if (!empty($result)) {
            
            if ($code != 200) {
                $code = $e->getStatusCode();
            }

            return response()->json($result, $code);
        } else {
            return parent::render($request, $e);
        }
    }
}
