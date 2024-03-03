<?php

namespace App\Exceptions;

use Core\Domain\Exceptions\EntityNotFoundException;
use Core\Domain\Exceptions\EntityValidationException;
use Core\Domain\Exceptions\SaleAlreadBeCancelledException;
use Core\Domain\Exceptions\UuidInvalidException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Response;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof EntityValidationException) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof EntityNotFoundException) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        if (
            $e instanceof UuidInvalidException ||
            $e instanceof SaleAlreadBeCancelledException
        ) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return parent::render($request, $e);
    }
}
