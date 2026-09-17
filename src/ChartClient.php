<?php

namespace Ridwan\LaravelPdfCharts;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Ridwan\LaravelPdfCharts\Exceptions\ChartException;

class ChartClient
{
    public function __construct(
        protected string $baseUrl,
        protected ?string $apiKey = null,
        protected int $timeout = 30,
    ) {}

    /**
     * POST to /chart and return the raw image binary.
     *
     * @param  array<string, mixed>  $payload
     *
     * @throws ChartException
     */
    public function render(array $payload): string
    {
        $payload = $this->withApiKey($payload);

        try {
            $response = $this->http()->asJson()->post("{$this->baseUrl}/chart", $payload);
        } catch (ConnectionException $e) {
            throw ChartException::requestFailed($e->getMessage(), $e);
        }

        if ($response->failed()) {
            throw ChartException::requestFailed(
                "HTTP {$response->status()}: {$response->body()}"
            );
        }

        $body = $response->body();

        if ($body === '') {
            throw ChartException::invalidResponse('Empty image body returned.');
        }

        return $body;
    }

    /**
     * POST to /chart/create and return a short render URL.
     *
     * @param  array<string, mixed>  $payload
     *
     * @throws ChartException
     */
    public function createUrl(array $payload): string
    {
        $payload = $this->withApiKey($payload);

        try {
            $response = $this->http()->asJson()->post("{$this->baseUrl}/chart/create", $payload);
        } catch (ConnectionException $e) {
            throw ChartException::requestFailed($e->getMessage(), $e);
        }

        if ($response->failed()) {
            throw ChartException::requestFailed(
                "HTTP {$response->status()}: {$response->body()}"
            );
        }

        $data = $response->json();

        if (! is_array($data) || empty($data['success']) || empty($data['url'])) {
            throw ChartException::invalidResponse($response->body());
        }

        return $data['url'];
    }

    /**
     * Build a GET URL for the chart (useful for embedding).
     *
     * @param  array<string, mixed>  $payload
     */
    public function buildGetUrl(array $payload): string
    {
        $payload = $this->withApiKey($payload);

        if (isset($payload['chart']) && is_array($payload['chart'])) {
            $payload['chart'] = json_encode($payload['chart']);
        }

        return "{$this->baseUrl}/chart?".http_build_query($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function withApiKey(array $payload): array
    {
        if ($this->apiKey !== null && $this->apiKey !== '') {
            $payload['key'] = $this->apiKey;
        }

        return $payload;
    }

    protected function http(): PendingRequest
    {
        return Http::timeout($this->timeout)->accept('*/*');
    }
}
