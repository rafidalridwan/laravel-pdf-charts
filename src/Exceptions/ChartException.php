<?php

namespace Ridwan\LaravelPdfCharts\Exceptions;

use Exception;
use Throwable;

class ChartException extends Exception
{
    public static function requestFailed(string $message, ?Throwable $previous = null): self
    {
        return new self("QuickChart request failed: {$message}", 0, $previous);
    }

    public static function invalidResponse(string $message): self
    {
        return new self("Invalid QuickChart response: {$message}");
    }

    public static function emptyChart(): self
    {
        return new self('Chart configuration is empty. Call Chart::make() with a Chart.js config first.');
    }
}
