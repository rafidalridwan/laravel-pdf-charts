<?php

namespace Ridwan\LaravelPdfCharts;

use Illuminate\Support\Facades\File;
use Ridwan\LaravelPdfCharts\Exceptions\ChartException;

class Chart
{
    /** @var array<string, mixed> */
    protected array $config = [];

    protected int $width;

    protected int $height;

    protected string $format;

    protected string $backgroundColor;

    protected float $devicePixelRatio;

    protected string $version;

    public function __construct(
        protected ChartClient $client,
        array $defaults = [],
    ) {
        $this->width = (int) ($defaults['width'] ?? 500);
        $this->height = (int) ($defaults['height'] ?? 300);
        $this->format = (string) ($defaults['format'] ?? 'png');
        $this->backgroundColor = (string) ($defaults['background_color'] ?? 'transparent');
        $this->devicePixelRatio = (float) ($defaults['device_pixel_ratio'] ?? 2.0);
        $this->version = (string) ($defaults['version'] ?? '4');
    }

    /**
     * Start a new chart with a Chart.js configuration.
     *
     * @param  array<string, mixed>  $config
     */
    public function make(array $config): static
    {
        $clone = clone $this;
        $clone->config = $config;

        return $clone;
    }

    public function width(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function size(int $width, int $height): static
    {
        return $this->width($width)->height($height);
    }

    /**
     * Set output format: png, svg, webp, or pdf.
     */
    public function format(string $format): static
    {
        $this->format = strtolower($format);

        return $this;
    }

    public function backgroundColor(string $color): static
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function devicePixelRatio(float $ratio): static
    {
        $this->devicePixelRatio = $ratio;

        return $this;
    }

    /**
     * Chart.js version string (e.g. "2", "3", "4").
     */
    public function version(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Merge additional keys into the Chart.js config.
     *
     * @param  array<string, mixed>  $config
     */
    public function config(array $config): static
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * Render the chart and return raw image/PDF binary.
     *
     * @throws ChartException
     */
    public function render(): string
    {
        return $this->client->render($this->payload());
    }

    /**
     * Create a short QuickChart URL for the chart.
     *
     * @throws ChartException
     */
    public function url(): string
    {
        return $this->client->createUrl($this->payload());
    }

    /**
     * Build a long GET URL (no API round-trip).
     */
    public function toUrl(): string
    {
        return $this->client->buildGetUrl($this->payload());
    }

    /**
     * Render and return a base64 data URI (or raw base64 if $dataUri is false).
     *
     * @throws ChartException
     */
    public function toBase64(bool $dataUri = true): string
    {
        $binary = $this->render();
        $encoded = base64_encode($binary);

        if (! $dataUri) {
            return $encoded;
        }

        $mime = match ($this->format) {
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            default => 'image/png',
        };

        return "data:{$mime};base64,{$encoded}";
    }

    /**
     * Render the chart and write it to disk. Returns the absolute path.
     *
     * @throws ChartException
     */
    public function save(string $path): string
    {
        $directory = dirname($path);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($path, $this->render());

        return $path;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ChartException
     */
    public function payload(): array
    {
        if ($this->config === []) {
            throw ChartException::emptyChart();
        }

        return [
            'chart' => $this->config,
            'width' => $this->width,
            'height' => $this->height,
            'format' => $this->format,
            'backgroundColor' => $this->backgroundColor,
            'devicePixelRatio' => $this->devicePixelRatio,
            'version' => $this->version,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
