<?php

return [
    'api_endpoint'=>'https://api.kanye.rest',
    'api_drivers' => [
        'default_driver'=> 'cache',
        'priority_driver' => env('PRIORITY_API_DRIVER')
    ],
];