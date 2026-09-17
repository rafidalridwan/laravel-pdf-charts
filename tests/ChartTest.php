<?php

namespace Ridwan\LaravelPdfCharts\Tests;

use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use Ridwan\LaravelPdfCharts\Chart;
use Ridwan\LaravelPdfCharts\ChartServiceProvider;
use Ridwan\LaravelPdfCharts\Exceptions\ChartException;
use Ridwan\LaravelPdfCharts\Facades\Chart as ChartFacade;

class ChartTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ChartServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Chart' => ChartFacade::class,
        ];
    }

    protected function sampleConfig(): array
    {
        return [
            'type' => 'bar',
            'data' => [
                'labels' => ['Jan', 'Feb', 'Mar'],
                'datasets' => [
                    [
                        'label' => 'Sales',
                        'data' => [120, 180, 150],
                    ],
                ],
            ],
        ];
    }

    public function test_make_builds_payload_with_defaults(): void
    {
        $chart = $this->app->make(Chart::class)->make($this->sampleConfig());

        $payload = $chart->payload();

        $this->assertSame($this->sampleConfig(), $payload['chart']);
        $this->assertSame(500, $payload['width']);
        $this->assertSame(300, $payload['height']);
        $this->assertSame('png', $payload['format']);
        $this->assertSame('transparent', $payload['backgroundColor']);
        $this->assertSame(2.0, $payload['devicePixelRatio']);
        $this->assertSame('4', $payload['version']);
    }

    public function test_fluent_options_override_defaults(): void
    {
        $payload = $this->app->make(Chart::class)
            ->make($this->sampleConfig())
            ->width(800)
            ->height(400)
            ->format('pdf')
            ->backgroundColor('white')
            ->devicePixelRatio(1)
            ->version('3')
            ->payload();

        $this->assertSame(800, $payload['width']);
        $this->assertSame(400, $payload['height']);
        $this->assertSame('pdf', $payload['format']);
        $this->assertSame('white', $payload['backgroundColor']);
        $this->assertSame(1.0, $payload['devicePixelRatio']);
        $this->assertSame('3', $payload['version']);
    }

    public function test_render_posts_to_quickchart_and_returns_binary(): void
    {
        Http::fake([
            'quickchart.io/chart' => Http::response('PNGDATA', 200),
        ]);

        $image = ChartFacade::make($this->sampleConfig())->render();

        $this->assertSame('PNGDATA', $image);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://quickchart.io/chart'
                && $request['format'] === 'png'
                && $request['chart']['type'] === 'bar';
        });
    }

    public function test_url_posts_to_create_endpoint(): void
    {
        Http::fake([
            'quickchart.io/chart/create' => Http::response([
                'success' => true,
                'url' => 'https://quickchart.io/chart/render/abc-123',
            ], 200),
        ]);

        $url = ChartFacade::make($this->sampleConfig())->url();

        $this->assertSame('https://quickchart.io/chart/render/abc-123', $url);
    }

    public function test_to_base64_returns_data_uri(): void
    {
        Http::fake([
            'quickchart.io/chart' => Http::response('PNGDATA', 200),
        ]);

        $uri = ChartFacade::make($this->sampleConfig())->toBase64();

        $this->assertSame('data:image/png;base64,'.base64_encode('PNGDATA'), $uri);
    }

    public function test_empty_config_throws(): void
    {
        $this->expectException(ChartException::class);

        $this->app->make(Chart::class)->render();
    }

    public function test_failed_http_response_throws(): void
    {
        Http::fake([
            'quickchart.io/chart' => Http::response('boom', 500),
        ]);

        $this->expectException(ChartException::class);

        ChartFacade::make($this->sampleConfig())->render();
    }

    public function test_to_url_builds_get_url_without_http(): void
    {
        $url = ChartFacade::make($this->sampleConfig())->width(100)->toUrl();

        $this->assertStringStartsWith('https://quickchart.io/chart?', $url);
        $this->assertStringContainsString('width=100', $url);
        $this->assertStringContainsString('format=png', $url);
    }
}
