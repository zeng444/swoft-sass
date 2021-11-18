<?php

use Application\Core\Components\Storage\Server as StorageServer;

/**
 *  集中服务
 */
$app->post('/attachments', function () use ($app) {

    if ($app->basicAuth->authorize() === false) {
        return $app->apiResponse->error($app->basicAuth->getErrorMessage(), $app->basicAuth->getErrorCode());
    }
    $rules = require ROOT_PATH . 'configs/user.config.php';
    $pathType = trim($app->request->getPost('pathType', 'trim', StorageServer::WEEK_PATH_TYPE));
    $rename = intval($app->request->getPost('rename', 'int', 1));
    $tag = trim($app->request->getPost('tag'));
    if (!$tag || !isset($rules[$tag])) {
        return $app->apiResponse->error('对不起tag不能为空', 5000);
    }
    $files = $this->request->getUploadedFiles();
    /** @var $storage $cloudStorage */
    $storage = $this->getDi()->get('storage');
    $storage->setRule($rules[$tag] ?? []);
    $storage->setPathType($pathType);
    $storage->setTag($tag);
    return $app->apiResponse->success(['attachments' => $storage->handle($files, $rename === 1)]);
});


/**
 *  删除业务
 */
$app->delete('/attachments', function () use ($app) {
    if ($app->basicAuth->authorize() === false) {
        return $app->apiResponse->error($app->basicAuth->getErrorMessage(), $app->basicAuth->getErrorCode());
    }
    $files = $app->request->getQuery('files');
    $files = is_array($files) ? $files : [];
    /** @var StorageServer $storage */
    $storage = $this->getDi()->get('storage');
    return $app->apiResponse->success(['attachments' => $storage->batchDelete($files)]);
});
