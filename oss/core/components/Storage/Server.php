<?php

namespace Application\Core\Components\Storage;

use Application\Core\Components\ErrorManager;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\DiInterface;
use Phalcon\Http\Request\File;

/**
 * Class Server
 * @author Robert
 * @package Application\Core\Components\CloudStorage
 */
class Server implements InjectionAwareInterface
{

    /**
     * Author:Robert
     *
     * @var
     */
    protected $_di;

    /**
     * @var
     */
    protected $uploadRootDir;

    /**
     * @var
     */
    protected $tag;

    /**
     * @var
     */
    protected $pathType;

    /**
     * @var
     */
    protected $rule = [];

    const DAY_PATH_TYPE = 'DAY';

    const WEEK_PATH_TYPE = 'WEEK';

    const MONTH_PATH_TYPE = 'MONTH';

    const PARAMS_ERROR_CODE = 5000;
    const MAX_SIZE_ERROR_CODE = 5001;
    const FILE_TYPE_ERROR_CODE = 5002;
    const TAG_ERROR_CODE = 5003;
    const FILE_NOT_EXIST_ERROR_CODE = 5404;
    const FILE_REMOVE_ERROR_CODE = 5501;

    /**
     * @param DiInterface $di
     */
    public function setDI(DiInterface $di)
    {
        $this->_di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->_di;
    }

    /**
     * @param string $tag
     * @return $this
     * @author Robert
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     * @author Robert
     */
    public function setPathType(string $type): self
    {
        $this->pathType = $type;
        return $this;
    }

    /**
     * @param array $rule
     * @return $this
     * @author Robert
     */
    public function setRule(array $rule): self
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param array $files
     * @param bool $rename
     * @return array
     * @author Robert
     */
    public function handle(array $files, bool $rename = false): array
    {
        $data = [];
        foreach ($files as $file) {
            $data[] = $this->upload($file, $rename);
        }
        return $data;
    }

    /**
     * @param File $file
     * @param bool $rename
     * @return array
     * @author Robert
     */
    public function upload(File $file, bool $rename = true): array
    {
        $result = [
            'code' => 0,
            'path' => '',
            'original' => '',
            'url' => '',
        ];
        $config = $this->getDi()->get('config');
        $realType = strtolower($file->getRealType());
        $size = $file->getSize();
        $original = $file->getName();
        $extension = $file->getExtension();
        if (!in_array($this->pathType, [self::DAY_PATH_TYPE, self::MONTH_PATH_TYPE, self::WEEK_PATH_TYPE])) {
            $result['code'] = self::PARAMS_ERROR_CODE;
            return $result;
        }
        if (!preg_match('/^[\w\.]+$/', $this->tag)) {
            $result['code'] = self::PARAMS_ERROR_CODE;
            return $result;
        }
        if (isset($this->rule['tags']) && !in_array($this->tag, $this->rule['tags'])) {
            $result['code'] = self::TAG_ERROR_CODE;
            return $result;
        }
        if (isset($this->rule['maxSize']) && $size > $this->rule['maxSize'] * 1048576) {
            $result['code'] = self::MAX_SIZE_ERROR_CODE;
            return $result;
        }
        if ($realType && isset($this->rule['allowed']) && !in_array($realType, $this->rule['allowed'])) {
            $result['code'] = self::FILE_TYPE_ERROR_CODE;
            return $result;
        }
        $uploadFileDir = $config->application->uploadFileDir;
        $tagFolder = $this->tag;
        if (!is_dir($uploadFileDir . $tagFolder)) {
            @mkdir($uploadFileDir . $tagFolder);
        }
        $extension = $extension ? '.' . $extension : $this->getRealExtension($realType);
        $fileName = $rename ? $this->generateFileName() . $extension : $file->getName();
        $distFolder = $tagFolder . DIRECTORY_SEPARATOR . $this->customPath($this->pathType);
        if (!is_dir($uploadFileDir . $distFolder)) {
            @mkdir($uploadFileDir . $distFolder);
        }
        if ($file->moveTo($uploadFileDir . $distFolder . DIRECTORY_SEPARATOR . $fileName) === false) {
            $result['code'] = self::PARAMS_ERROR_CODE;
            return $result;
        }
        $result['path'] = $distFolder . DIRECTORY_SEPARATOR . $fileName;
        $result['url'] = $config->application->imagePrefix . "$distFolder/$fileName";
        $result['original'] = $original;
        return $result;
    }

    /**
     * @param string $filePath
     * @return array
     * @author Robert
     */
    public function delete(string $filePath): array
    {
        $config = $this->getDi()->get('config');
        $result = [
            'code' => 0,
            'path' => $filePath,
            'url' => $config->application->imagePrefix . $filePath,
        ];
        $uploadFileDir = $config->application->uploadFileDir;
        $dist = $uploadFileDir . $filePath;
        if (!file_exists($dist)) {
            $result['code'] = self::FILE_NOT_EXIST_ERROR_CODE;
            return $result;
        }
        if(!@unlink($dist)){
            $result['code'] = self::FILE_REMOVE_ERROR_CODE;
            return $result;
        }
        return $result;
    }

    /**
     * @param array $files
     * @return array
     * @author Robert
     */
    public function batchDelete(array $files): array
    {
        $data = [];
        foreach ($files as $file) {
            $data[] = $this->delete($file);
        }
        return $data;
    }


    /**
     * @return string
     * @author Robert
     */
    protected function generateFileName(): string
    {
        return crc32(uniqid(true));
    }

    /**
     * @param string $pathType
     * @return string
     * @author Robert
     */
    protected function customPath(string $pathType): string
    {
        switch ($pathType) {
            case self::DAY_PATH_TYPE:
                $path = date('Ymd');
                break;
            case self::WEEK_PATH_TYPE:
                $path = date('YW');
                break;
            case self::MONTH_PATH_TYPE:
                $path = date('Ym');
                break;
            default:
                $path = date('YmdH');
                break;
        }
        return $path;
    }

    /**
     * @param string $realType
     * @return string
     * @author Robert
     */
    protected function getRealExtension(string $realType): string
    {
        return $this->mime()[$realType] ?? '';
    }

    /**
     * @return string[]
     * @author Robert
     */
    protected function mime(): array
    {
        return [
            'image/jpeg' => '.jpeg',
            'image/pjpeg' => '.jpeg',
            'image/jpg' => '.jpeg',
            'image/png' => '.png',
            'image/x-png' => '.png',
            'application/zip' => '.zip',
            'application/x-rar' => '.rar',
            'application/x-zip-compressed' => '.zip',
            'application/pdf' => '.pdf',
            'application/vnd.ms-powerpoint' => '.ppt',
            'application/vnd.ms-excel' => '.xls',
            'application/msword' => '.doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => '.pptx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
            'application/x-gzip' => '.gz',
            'image/gif' => '.gif',
            'application/x-tar' => '.tar',
            'text/csv' => '.csv',
            'text/plain' => '.csv',
        ];
    }
}