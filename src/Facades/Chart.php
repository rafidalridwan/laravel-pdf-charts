<?php

namespace Ridwan\LaravelPdfCharts\Facades;

use Illuminate\Support\Facades\Facade;
use Ridwan\LaravelPdfCharts\Chart as ChartBuilder;

/**
 * @method static ChartBuilder make(array $config)
 * @method static ChartBuilder width(int $width)
 * @method static ChartBuilder height(int $height)
 * @method static ChartBuilder size(int $width, int $height)
 * @method static ChartBuilder format(string $format)
 * @method static ChartBuilder backgroundColor(string $color)
 * @method static ChartBuilder devicePixelRatio(float $ratio)
 * @method static ChartBuilder version(string $version)
 * @method static ChartBuilder config(array $config)
 * @method static string render()
 * @method static string url()
 * @method static string toUrl()
 * @method static string toBase64(bool $dataUri = true)
 * @method static string save(string $path)
 * @method static array payload()
 * @method static array getConfig()
 *
 * @see ChartBuilder
 */
class Chart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChartBuilder::class;
    }
}
