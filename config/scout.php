<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scout Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default search engine that will be used by
    | Scout for all search operations. Supported: "algolia", "database", etc.
    |
    */

    'driver' => env('SCOUT_DRIVER', 'algolia'),

    /*
    |--------------------------------------------------------------------------
    | Algolia Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Algolia credentials.
    |
    */

    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
    ],

    // ... zingine kama zinahitajika

];
