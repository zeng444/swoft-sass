<?php declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use App\Rpc\Client\Contract\Extender;
use App\Rpc\Client\Contract\RpcProvider;
use Swoft\Db\Database;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Redis\RedisDb;
use App\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Server\SwooleEvent;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\WebSocket\Server\WebSocketServer;
use App\Rpc\Client\Contract\Balancer;

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
//        'levels'    => 'notice,info,debug,trace',
        'levels' => 'info,debug,trace',
    ],
    'logger' => [
        'flushRequest' => false,
        'enable' => true,
        'json' => false,
    ],
    'httpServer' => [
        'class' => HttpServer::class,
        'port' => env('HTTP_PORT', 18306),
        'listener' => [
            // 'rpc' => bean('rpcServer'),
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
            'task_worker_num' => env('HTTP_TASK_WORK_NUMBER', 1),
            'task_enable_coroutine' => true,
            'worker_num' => env('HTTP_WORK_NUMBER', 2),
            'buffer_output_size' => 20 * 1024 * 1024,

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
            \App\Http\Middleware\AclMiddleware::class,
        ],
        'afterMiddlewares' => [
            \App\Http\Middleware\AfterAuthMiddleware::class,
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class,
        ]
    ],
    \Swoft\Auth\Contract\AuthManagerInterface::class => [
        'class' => \App\Http\Auth\AuthManagerService::class,
    ],
    \Swoft\Auth\Contract\AuthServiceInterface::class => [
        'class' => \App\Http\Auth\Acl\AuthService::class,
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
        'charset' => 'utf8mb4',
        'config' => [
            'collation' => 'utf8mb4_unicode_ci',
            'strict' => true,
        ],
        'options' => [
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
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
        'database' => 0,
        'option' => [
            'prefix' => env('REDIS_PREFIX', 'swoft:'),
            'serializer' => Redis::SERIALIZER_NONE
        ]
    ],
//    'rpcServer'          => [
//        'class' => ServiceServer::class,
//        'listener' => [
//            'http' => bean('httpServer'),
//        ]
//    ],
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
    'biz' => [
        'class' => ServiceClient::class,
//        'host' => 'swoft-cat-service',
//        'port' => 18307,
        'setting' => [

            'timeout' => 60,//总超时，包括连接、发送、接收所有超时
            'connect_timeout' => 2,//连接超时，会覆盖第一个总的 timeout
            'write_timeout' => 60,//发送超时，会覆盖第一个总的 timeout
            'read_timeout' => 60,//接收超时，会覆盖第一个总的 timeout
            'package_max_length' => 20*1024*1024,
        ],
        'extender' => bean(Extender::class),
        'provider' => bean(RpcProvider::class),
        'packet' => bean('rpcClientPacket'),
        'balancer' => bean(Balancer::class)
    ],
    'biz.pool' => [
        'class' => ServicePool::class,
        'client' => bean('biz'),
    ],
];
