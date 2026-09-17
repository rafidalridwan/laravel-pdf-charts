<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QuickChart API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for QuickChart's API. Override this if you are self-hosting
    | QuickChart or using a custom endpoint.
    |
    */

    'base_url' => env('PDF_CHARTS_BASE_URL', 'https://quickchart.io'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Optional QuickChart API key for authenticated / rate-limited usage.
    | Leave null to use the public endpoint.
    |
    */

    'api_key' => env('PDF_CHARTS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Chart Options
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'width' => (int) env('PDF_CHARTS_WIDTH', 500),
        'height' => (int) env('PDF_CHARTS_HEIGHT', 300),
        'format' => env('PDF_CHARTS_FORMAT', 'png'),
        'background_color' => env('PDF_CHARTS_BACKGROUND', 'transparent'),
        'device_pixel_ratio' => (float) env('PDF_CHARTS_DEVICE_PIXEL_RATIO', 2.0),
        'version' => env('PDF_CHARTS_CHARTJS_VERSION', '4'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('PDF_CHARTS_TIMEOUT', 30),

];
