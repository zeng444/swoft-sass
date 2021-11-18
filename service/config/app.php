<?php

return [
    'env' => env('APP_ENV', 'PROD'),
    'allowedOrigin' => explode(',', env('ALLOWED_ORIGIN')),
    'serviceCode' => env('SERVICE_CODE', ''),
];
