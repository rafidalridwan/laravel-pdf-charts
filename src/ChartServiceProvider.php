<?php

namespace Ridwan\LaravelPdfCharts;

use Illuminate\Support\ServiceProvider;

class ChartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pdf-charts.php', 'pdf-charts');

        $this->app->singleton(ChartClient::class, function ($app) {
            $config = $app['config']['pdf-charts'];

            return new ChartClient(
                baseUrl: rtrim($config['base_url'], '/'),
                apiKey: $config['api_key'] ?? null,
                timeout: (int) ($config['timeout'] ?? 30),
            );
        });

        $this->app->bind(Chart::class, function ($app) {
            return new Chart(
                client: $app->make(ChartClient::class),
                defaults: $app['config']['pdf-charts']['defaults'] ?? [],
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pdf-charts.php' => config_path('pdf-charts.php'),
            ], 'pdf-charts-config');
        }
    }
}
