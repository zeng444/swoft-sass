<?php declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use App\Rpc\Middleware\ServiceMiddleware;
use App\Common\Db\Database;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Redis\RedisDb;
use Swoft\Rpc\Server\ServiceDispatcher;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Server\SwooleEvent;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\WebSocket\Server\WebSocketServer;

return [
    'config' => [
        'path' => __DIR__ . '/../config',
    ],
    'applicationHandler' => [
        'logFile' => '@runtime/logs/error.log',
        'levels' => 'error,warning',
    ],
    'noticeHandler' => [
        'logFile' => '@runtime/logs/notice-%d{Y-m-d}.log',
        'levels' => 'info,debug,trace',
//        'levels'    => 'notice,info,debug,trace',
    ],
    'logger' => [
        'flushRequest' => false,
        'enable' => true,
        'json' => false,
    ],
    'httpServer' => [
        'class' => HttpServer::class,
        'port' => 18306,
        'listener' => [
            'rpc' => bean('rpcServer'),
            // 'tcp' => bean('tcpServer'),
        ],
        'process' => [
            // 'monitor' => bean(\App\Process\MonitorProcess::class)
            'crontab' => bean(Swoft\Crontab\Process\CrontabProcess::class)
        ],
        'on' => [
            // SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
            SwooleEvent::TASK => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting' => [
            'task_worker_num' => 1,
            'task_enable_coroutine' => true,
            'worker_num' => 2,
            // static handle
            // 'enable_static_handler'    => true,
            // 'document_root'            => dirname(__DIR__) . '/public',
        ]
    ],
    'httpDispatcher' => [
        // Add global http middleware
        'middlewares' => [
            \App\Http\Middleware\FavIconMiddleware::class,
            \App\Http\Middleware\CrossMiddleware::class,
        ],
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class,
        ]
    ],
//    'db'                 => [
//        'class'    => Database::class,
//        'dsn'      => env('DB_DSN'),
//        'username' => env('DB_USER'),
//        'password' => env('DB_PASS'),
//        'charset'  => 'utf8mb4',
//        'config' => [
//            'collation' => 'utf8mb4_unicode_ci',
//            'strict' => true,
//        ],
//    ],
    'db' => [
        'class' => Database::class,
        'dbSelector' => bean(App\Common\Db\DbSelector::class),
        'charset' => 'utf8mb4',
        'config' => [
            'collation' => 'utf8mb4_unicode_ci',
            'strict' => true,
        ],
        'writes' => [
            [
                'dsn' => env('DB_DSN'),
                'username' => env('DB_USER'),
                'password' => env('DB_PASS'),
            ]
        ],
        'reads' => [
            [
                'dsn' => env('DB_DSN_SLAVE1'),
                'username' => env('DB_USERNAME_SLAVE1'),
                'password' => env('DB_PASSWORD_SLAVE1'),
            ]
        ]

    ],
    'db.pool' => [
        'class' => Pool::class,
        'database' => bean('db'),
    ],
    'migrationManager' => [
        'migrationPath' => '@database/Migration',
    ],
    'redis' => [
        'class' => RedisDb::class,
        'host' => env('REDIS_SERVER', 'redis'),
        'port' => 6379,
        'database' => env('REDIS_DATABASE', 0),
        'option' => [
            'prefix' => env('REDIS_PREFIX', 'swoft:'),
            'serializer' => Redis::SERIALIZER_NONE
        ]
    ],
    'rpcServer' => [
        'class' => ServiceServer::class,
        'port' => 18307,
        'on' => [
            SwooleEvent::TASK => \bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => \bean(FinishListener::class)
        ],
        'setting' => [
            'task_worker_num' => env('RPC_TASK_WORK_NUMBER', 3),
            'worker_num' => env('RPC_WORK_NUMBER', 2),
            'task_enable_coroutine' => true,
            'buffer_output_size' => 20 * 1024 * 1024,
            'package_max_length' => 20 * 1024 * 1024,
        ]
//        'listener' => [
//            'http' => bean('httpServer'),
//        ]
    ],
    'processPool' => [
        'class' => \Swoft\Process\ProcessPool::class,
        'workerNum' => env("PROCESS_WORKER_NUMBER", 3),
        'coroutine' => true
    ],
    'serviceDispatcher' => [
        'class' => ServiceDispatcher::class,
        'middlewares' => [
            ServiceMiddleware::class,
        ],
    ],
    'wsServer' => [
        'class' => WebSocketServer::class,
        'port' => 18308,
        'listener' => [
            'rpc' => bean('rpcServer'),
            // 'tcp' => bean('tcpServer'),
        ],
        'on' => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
            // Enable task must add task and finish event
            SwooleEvent::TASK => bean(TaskListener::class),
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        'debug' => 1,
        // 'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'task_worker_num' => 6,
            'task_enable_coroutine' => true,
            'worker_num' => 6,
            'log_file' => alias('@runtime/swoole.log'),
            // 'open_websocket_close_frame' => true,
        ],
    ],
    // 'wsConnectionManager' => [
    //     'storage' => bean('wsConnectionStorage')
    // ],
    // 'wsConnectionStorage' => [
    //     'class' => \Swoft\Session\SwooleStorage::class,
    // ],
    /** @see \Swoft\WebSocket\Server\WsMessageDispatcher */
    'wsMsgDispatcher' => [
        'middlewares' => [
            \App\WebSocket\Middleware\GlobalWsMiddleware::class
        ],
    ],
    /** @see \Swoft\Tcp\Server\TcpServer */
    'tcpServer' => [
        'port' => 18309,
        'debug' => 1,
    ],
    /** @see \Swoft\Tcp\Protocol */
    'tcpServerProtocol' => [
        // 'type' => \Swoft\Tcp\Packer\JsonPacker::TYPE,
        'type' => \Swoft\Tcp\Packer\SimpleTokenPacker::TYPE,
        // 'openLengthCheck' => true,
    ],
    /** @see \Swoft\Tcp\Server\TcpDispatcher */
    'tcpDispatcher' => [
        'middlewares' => [
            \App\Tcp\Middleware\GlobalTcpMiddleware::class
        ],
    ],
    'cliRouter' => [// 'disabledGroups' => ['demo', 'test'],
    ],
];
