# Laravel PDF Charts

A Laravel package for generating chart images (PNG, SVG, WebP, PDF) from Chart.js-style configurations.

```
Laravel Application
       ↓
ridwan/laravel-pdf-charts
       ↓
Chart Image
       ↓
Laravel Application
```

## Installation

```bash
composer require ridwan/laravel-pdf-charts
```

Publish the config (optional):

```bash
php artisan vendor:publish --tag=pdf-charts-config
```

## Supported charts

Set the chart type with `'type' => '...'` in the config passed to `Chart::make()`.

| Type | Description |
|---|---|
| `bar` | Vertical or horizontal bars for comparing values across categories |
| `line` | Connected points for trends over time or ordered categories |
| `pie` | Circular segments showing parts of a whole |
| `doughnut` | Like a pie chart, with the center cut out |
| `radar` | Multi-axis spider/web chart for comparing several variables |
| `polarArea` | Circular segments where both angle and radius reflect value |
| `scatter` | Individual `(x, y)` points for correlation or distribution |
| `bubble` | Like scatter, plus a radius `r` for a third data dimension |

You can also build **mixed charts** by setting a different `type` on each dataset.

### Bar

```php
Chart::make([
    'type' => 'bar',
    'data' => [
        'labels' => ['Jan', 'Feb', 'Mar'],
        'datasets' => [
            ['label' => 'Sales', 'data' => [120, 180, 150]],
        ],
    ],
])->render();
```

### Line

```php
Chart::make([
    'type' => 'line',
    'data' => [
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        'datasets' => [
            ['label' => 'Visitors', 'data' => [12, 19, 8, 15, 22], 'fill' => false],
        ],
    ],
])->render();
```

### Pie

```php
Chart::make([
    'type' => 'pie',
    'data' => [
        'labels' => ['Red', 'Blue', 'Yellow'],
        'datasets' => [
            ['data' => [300, 50, 100]],
        ],
    ],
])->render();
```

### Doughnut

```php
Chart::make([
    'type' => 'doughnut',
    'data' => [
        'labels' => ['Desktop', 'Mobile', 'Tablet'],
        'datasets' => [
            ['data' => [55, 35, 10]],
        ],
    ],
])->render();
```

### Radar

```php
Chart::make([
    'type' => 'radar',
    'data' => [
        'labels' => ['Speed', 'Power', 'Range', 'Durability', 'Accuracy'],
        'datasets' => [
            ['label' => 'Player A', 'data' => [65, 59, 90, 81, 56]],
        ],
    ],
])->render();
```

### Polar area

```php
Chart::make([
    'type' => 'polarArea',
    'data' => [
        'labels' => ['Red', 'Green', 'Yellow', 'Grey', 'Blue'],
        'datasets' => [
            ['data' => [11, 16, 7, 3, 14]],
        ],
    ],
])->render();
```

### Scatter

```php
Chart::make([
    'type' => 'scatter',
    'data' => [
        'datasets' => [
            [
                'label' => 'Scatter Dataset',
                'data' => [
                    ['x' => -10, 'y' => 0],
                    ['x' => 0, 'y' => 10],
                    ['x' => 10, 'y' => 5],
                ],
            ],
        ],
    ],
])->render();
```

### Bubble

```php
Chart::make([
    'type' => 'bubble',
    'data' => [
        'datasets' => [
            [
                'label' => 'Bubbles',
                'data' => [
                    ['x' => 20, 'y' => 30, 'r' => 15],
                    ['x' => 40, 'y' => 10, 'r' => 10],
                    ['x' => 30, 'y' => 20, 'r' => 25],
                ],
            ],
        ],
    ],
])->render();
```

## Usage

```php
use Ridwan\LaravelPdfCharts\Facades\Chart;

$image = Chart::make([
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
])->render();
```

### Fluent options

```php
$image = Chart::make($config)
    ->width(800)
    ->height(400)
    ->format('png')           // png | svg | webp | pdf
    ->backgroundColor('white')
    ->devicePixelRatio(2)
    ->version('4')
    ->render();
```


### Base64 / data URI

```php
$dataUri = Chart::make($config)->toBase64();
$raw = Chart::make($config)->toBase64(dataUri: false);
```

### Save to disk

```php
$path = Chart::make($config)->save(storage_path('app/charts/sales.png'));
```

### Dependency injection

```php
use Ridwan\LaravelPdfCharts\Chart;

public function __invoke(Chart $chart)
{
    return $chart->make([...])->render();
}
```


## License

MIT
