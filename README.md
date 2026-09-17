# Laravel PDF Charts

Generate beautiful, server-side chart images and PDFs in Laravel using **Chart.js-style configurations** — without requiring a browser or external chart service in your Laravel application.

[![Latest Version](https://img.shields.io/packagist/v/ridwan/laravel-pdf-charts.svg?style=flat-square)](https://packagist.org/packages/ridwan/laravel-pdf-charts)
[![PHP Version](https://img.shields.io/packagist/php-v/ridwan/laravel-pdf-charts.svg?style=flat-square)](https://packagist.org/packages/ridwan/laravel-pdf-charts)
[![License](https://img.shields.io/packagist/l/ridwan/laravel-pdf-charts.svg?style=flat-square)](LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/rafidalridwan/laravel-pdf-charts?style=flat-square)](https://github.com/rafidalridwan/laravel-pdf-charts)

---

## ✨ Features

- 📊 Generate charts directly from Laravel
- 🧩 Chart.js-style configuration
- 🖼️ Export charts as **PNG, SVG, WebP, or PDF**
- ⚡ Server-side rendering
- 🎨 Custom chart dimensions and background colors
- 🔍 Configurable device pixel ratio
- 🔄 Support for Chart.js versions
- 💾 Save generated charts directly to disk
- 🔢 Generate Base64 or Data URI representations
- 🧱 Fluent API
- 💉 Laravel dependency injection support
- 📦 Simple Composer installation
- 🪶 No browser required in your Laravel application

---

## 📦 Installation

Install the package using Composer:

```bash
composer require ridwan/laravel-pdf-charts
```

### Publish Configuration

Publishing the configuration file is optional:

```bash
php artisan vendor:publish --tag=pdf-charts-config
```

After publishing, you can customize the package configuration according to your application's requirements.

---

## 🚀 Quick Start

Import the `Chart` facade:

```php
use Ridwan\LaravelPdfCharts\Facades\Chart;
```

Then create a chart using a Chart.js-style configuration:

```php
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

The generated chart is returned as binary output, which you can use in your Laravel application.

---

# 📊 Supported Charts

The package supports the following chart types:

| Type        | Description                                                    |
| ----------- | -------------------------------------------------------------- |
| `bar`       | Vertical or horizontal bars for comparing values               |
| `line`      | Connected points for displaying trends                         |
| `pie`       | Circular segments representing parts of a whole                |
| `doughnut`  | Pie chart with a cut-out center                                |
| `radar`     | Multi-axis spider/web chart for comparing variables            |
| `polarArea` | Circular segments where angle and radius represent values      |
| `scatter`   | Individual `(x, y)` points for correlation or distribution     |
| `bubble`    | Scatter-style chart with radius representing a third dimension |

You can also create **mixed charts** by specifying different chart types for individual datasets.

---

# 📈 Chart Examples

## Bar Chart

```php
Chart::make([
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

---

## Line Chart

```php
Chart::make([
    'type' => 'line',

    'data' => [
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],

        'datasets' => [
            [
                'label' => 'Visitors',
                'data' => [12, 19, 8, 15, 22],
                'fill' => false,
            ],
        ],
    ],
])->render();
```

---

## Pie Chart

```php
Chart::make([
    'type' => 'pie',

    'data' => [
        'labels' => ['Red', 'Blue', 'Yellow'],

        'datasets' => [
            [
                'data' => [300, 50, 100],
            ],
        ],
    ],
])->render();
```

---

## Doughnut Chart

```php
Chart::make([
    'type' => 'doughnut',

    'data' => [
        'labels' => ['Desktop', 'Mobile', 'Tablet'],

        'datasets' => [
            [
                'data' => [55, 35, 10],
            ],
        ],
    ],
])->render();
```

---

## Radar Chart

```php
Chart::make([
    'type' => 'radar',

    'data' => [
        'labels' => [
            'Speed',
            'Power',
            'Range',
            'Durability',
            'Accuracy',
        ],

        'datasets' => [
            [
                'label' => 'Player A',
                'data' => [65, 59, 90, 81, 56],
            ],
        ],
    ],
])->render();
```

---

## Polar Area Chart

```php
Chart::make([
    'type' => 'polarArea',

    'data' => [
        'labels' => [
            'Red',
            'Green',
            'Yellow',
            'Grey',
            'Blue',
        ],

        'datasets' => [
            [
                'data' => [11, 16, 7, 3, 14],
            ],
        ],
    ],
])->render();
```

---

## Scatter Chart

Scatter charts use objects containing `x` and `y` values:

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

---

## Bubble Chart

Bubble charts support an additional `r` value representing the bubble radius:

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

---

# 🛠️ Fluent API

Customize the generated chart using the fluent API:

```php
$image = Chart::make($config)
    ->width(800)
    ->height(400)
    ->format('png')
    ->backgroundColor('white')
    ->devicePixelRatio(2)
    ->version('4')
    ->render();
```

### Available Options

| Method                     | Description                       |
| -------------------------- | --------------------------------- |
| `width(800)`               | Set chart width                   |
| `height(400)`              | Set chart height                  |
| `format('png')`            | Set output format                 |
| `backgroundColor('white')` | Set chart background              |
| `devicePixelRatio(2)`      | Configure rendering pixel density |
| `version('4')`             | Specify Chart.js version          |
| `render()`                 | Generate the chart                |

### Supported Formats

```text
png
svg
webp
pdf
```

For example:

```php
$chart = Chart::make($config)
    ->width(1200)
    ->height(600)
    ->format('png')
    ->render();
```

---

# 🔢 Base64 & Data URI

Generate a Base64 representation of the chart:

```php
$dataUri = Chart::make($config)->toBase64();
```

By default, the result can be returned as a Data URI.

If you only need the raw Base64 content:

```php
$raw = Chart::make($config)
    ->toBase64(dataUri: false);
```

This can be useful when embedding charts into HTML, emails, APIs, or other documents.

---

# 💾 Save Charts to Disk

Save the generated chart directly to a file:

```php
$path = Chart::make($config)
    ->save(storage_path('app/charts/sales.png'));
```

You can also select the desired format before saving:

```php
$path = Chart::make($config)
    ->format('pdf')
    ->save(storage_path('app/charts/sales.pdf'));
```

---

# 🏗️ How It Works

The package follows a simple rendering flow:

```text
┌─────────────────────┐
│   Laravel App       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────┐
│ ridwan/laravel-pdf-charts   │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────┐
│ Chart Configuration │
│   Chart.js Style    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Chart Renderer      │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────┐
│ PNG / SVG / WebP / PDF      │
└─────────────────────────────┘
```

The goal is to keep chart generation inside your Laravel application while exposing a simple PHP API.

---

# 📜 License

This package is open-sourced software licensed under the **MIT License**.

See the [`LICENSE`](LICENSE) file for more information.

---

# ⭐ Support

If this package helps you build better Laravel applications, consider giving the repository a ⭐ on GitHub.

Your feedback, issues, and contributions are also welcome.

---

## 📦 Package

**Package:** `ridwan/laravel-pdf-charts`

**Purpose:** Server-side chart generation for Laravel

**Output formats:** PNG · SVG · WebP · PDF

**Chart configuration:** Chart.js-style

**License:** MIT
