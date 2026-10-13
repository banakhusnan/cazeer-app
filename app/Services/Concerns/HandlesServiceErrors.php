<?php

namespace App\Services\Concerns;

use Closure;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesServiceErrors
{
    protected function execute(string $operation, Closure $callback): mixed
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            Log::error('Operasi service gagal.', [
                'operation' => $operation,
                'user_id' => auth()->id(),
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
