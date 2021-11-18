<?php

use Application\Core\Components\Storage\Server as StorageServer;

/**
 *  删除业务
 */
$app->delete('/oss', function () use ($app) {
    if ($app->ossAuth->authorize() === false) {
        return $app->apiResponse->error($app->ossAuth->getErrorMessage(), $app->ossAuth->getErrorCode());
    }
    $files = $app->request->getQuery('files');
    $files = is_array($files) ? $files : [];
    /** @var StorageServer $cloudStorage */
    $cloudStorage = $this->getDi()->get('storage');
    return $app->apiResponse->success($cloudStorage->batchDelete($files));
});

/**
 *
 */
$app->post('/oss', function () use ($app) {
    if ($app->ossAuth->authorize() === false) {
        return $app->apiResponse->error($app->ossAuth->getErrorMessage(), $app->ossAuth->getErrorCode());
    }
    $tag = trim($app->request->getPost('tag'));
    $pathType = trim($app->request->getPost('pathType', 'trim', StorageServer::WEEK_PATH_TYPE));
    $rename = intval($app->request->getPost('rename', 'int', 1));

    $files = $this->request->getUploadedFiles();
    if(!$files){
        return $app->apiResponse->error('file not exits');
    }
    error_log(serialize($files).PHP_EOL,3,'logs.log');
    /** @var StorageServer $storage */
    $storage = $this->getDi()->get('storage');
    $storage->setRule($rules[$tag] ?? []);
    $storage->setPathType($pathType);
    $storage->setTag($tag);
    return $app->apiResponse->success(['attachments' => $storage->handle($files, $rename === 1)]);

});