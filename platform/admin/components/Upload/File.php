<?php
namespace Application\Admin\Components\Upload;

use Phalcon\Di;

class File
{
    //const  MAX_IMAGE_FILE_SIZE = 2097152;

    public static $allowImageMimeType = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    public static $allowFileMimeType = [
        //        'application/x-zip-compressed',
        //        'application/octet-stream',
        //        'application/x-gzip',
        //        'application/msword',
        //        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        //        'application/vnd.ms-excel',

    ];

    public static $allowSize = 10485760; //10M

    /**
     * Author:Robert
     *
     * @param $extension
     * @return string
     */
    public static function makeFileName($extension)
    {
        return md5(uniqid(true)).'.'.$extension;
    }

    /**
     * 生成图片上传的目录和名称
     *
     * @param $extension
     * @return array
     */
    public static function getSaveInfo($extension)
    {
        return [
            'fileName' => self::makeFileName($extension),
            'subPath' => date('YW').DIRECTORY_SEPARATOR,
        ];
    }

    /**
     * Author:Robert
     *
     * @param $file
     * @param array $resizeInfo [width,height]
     * @return array
     * @throws \Exception
     */
    public static function uploadImage($file, $resizeInfo = [])
    {
        $result = ['code' => 0, 'data' => ''];
        $di = Di::getDefault();
        $config = $di->get('config');
        if ($file->getSize() > self::$allowSize) {
            $result['code'] = -1;
            $result['message'] = "文件太大了。应该小于".round(self::$allowSize).'K';
            return $result;
        }
        $allowMimeType = array_merge(self::$allowFileMimeType, self::$allowImageMimeType);
        $fileType = $file->getType();
        if (!in_array($fileType, $allowMimeType)) {
            $result['code'] = -2;
            $result['message'] = '“'.$file->getExtension().'”不允许的文件类型';
            return $result;
        }

        $imageSaveInfo = self::getSaveInfo($file->getExtension());
        $uploadFileDir = $config->application->uploadFileDir;
        if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0777)) {
            $result['code'] = -3;
            $result['message'] = "上传目录没有权限 $uploadFileDir";
            return $result;
        }
        $path = $uploadFileDir.$imageSaveInfo['subPath'];
        if (!is_dir($path) && !mkdir($path, 0777)) {
            $result['code'] = -3;
            $result['message'] = "上传目录没有权限 $path";
            return $result;
        }

        $distPath = $path.$imageSaveInfo['fileName'];
        if (!$file->moveTo($distPath)) {
            $result['code'] = -5;
            $result['message'] = "文件上传失败";
            return $result;
        }

        if (in_array($fileType, self::$allowImageMimeType)) {
            $imagesize = getimagesize($distPath);
            if (!$imagesize) {
                $result['code'] = -2;
                $result['message'] = '不合法的文件类型格式';
                return $result;
            }
            //应用图形裁剪和自动修复图形旋转状态，通过GD imagecreatefromjpeg去除图片不安全问题
            $imageResize = new \Application\Admin\Components\Graph\ImageClipper($distPath);
            if (!empty($resizeInfo)) {
                list($sourceWidth,) = $imagesize;
                if ($sourceWidth) {
                    if (intval($sourceWidth) <= $resizeInfo['width']) {
                        $resizeInfo['height'] = ceil(($sourceWidth * $resizeInfo['height']) / $resizeInfo['width']);
                        $resizeInfo['width'] = $sourceWidth;
                    }
                    $imageResize->resizeTo($resizeInfo['width'], $resizeInfo['height']);
                }
            }
            try {
                $imageResize->execute($distPath);
            } catch (\Exception $e) {
                $result['code'] = -6;
                $result['message'] = "异常";
                return $result;
            }
        }
        $subfile = $imageSaveInfo['subPath'].$imageSaveInfo['fileName'];
        $imageSaveInfo['subfile'] = $subfile;
        $imageSaveInfo['image'] = $config->application->imagePrefix.$subfile;
        $result['data'] = $imageSaveInfo;
        return $result;
    }
}
