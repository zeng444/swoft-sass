<?php

return [
    'host' => env('BEANSTALKD_HOST'),
    'port' => env('BEANSTALKD_PORT'),
    'tube' => env('BEANSTALKD_TUBE'),
    'maxRequest' => 500,
];
