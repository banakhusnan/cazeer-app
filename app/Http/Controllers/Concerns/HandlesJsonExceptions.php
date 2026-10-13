<?php

namespace App\Http\Controllers\Concerns;

use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

trait HandlesJsonExceptions
{
    protected function jsonFailure(Throwable $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return ApiResponse::error('Data yang diberikan tidak valid.', 422, $exception->errors());
        }

        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::notFound();
        }

        return ApiResponse::error('Terjadi kesalahan. Silakan coba kembali.');
    }
}
