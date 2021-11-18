<?php
return [
    'jwt' => [
        'algorithm' => env('AUTH_ALGORITHM', 'HS256'),
        'secret' => env('AUTH_TOKEN_SECRET'),
        'expire' => env('AUTH_TOKEN_EXPIRE', 7200),
    ],
];
