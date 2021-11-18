<?php
/**
 * 权限菜单列表
 */
return [
    'ignore' => [
        'POST:/userSessions',
        'POST:/voiceNotifications',
        'GET:/menus',
        'GET:/systemBrands',
        'GET:/versions',
        'GET:/quoteBatches',
        'GET:/systemMenus',
        'GET:/selectorOptions',
        'POST:/quoteBatches/{batchNo}',
        'POST:/quotes/analysis',
        'GET:/vpn',
        'POST:/analysis/{fileId}',
        'GET:/analysis/{fileId}',
        'GET:/systemCoverages',
        'GET:/messageStatistics',
    ]
];
