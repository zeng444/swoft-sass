<?php
namespace Application\Admin\Components\Graph;

/**
 * 图片裁剪器
 * @author 850328994@qq.com
 * @mail zeng444@163.com
 *
 * Class ImageClipper
 * @package Application\Core\Components\Graph
 */
class ImageClipper
{

    /**
     * 原始图路径路径
     * @var
     */
    public $srcImage;

    /**
     * 原始图宽
     * @var
     */
    protected $srcImageWidth;

    /**
     * 原始图高
     * @var
     */
    protected $srcImageHeight;

    /**
     * 原始图类型
     * @var
     */
    protected $srcImageMime;

    /**
     * 图片旋转信息
     * @var
     */
    protected $srcImageOrientation;


    /**
     * 目标图大小
     * @var
     */
    protected $distImageWidth;

    /**
     * 目标图高度
     * @var
     */
    protected $distImageHeight;

    /**
     * 允许的文件类型
     * @var array
     */
    protected $allowType = ['image/jpeg', 'image/png'];

    /**
     * 设置图片压缩的尺寸
     * @var int
     */
    protected $quality = 1;

    /**
     * 画布
     * @var resource
     */
    private $canvasObj;

    /**
     * 裁剪对象
     * @var null|resource
     */
    private $imageObj;


    /**
     * @param $srcImage
     * @param array $config
     * @throws \Exception
     */
    public function __construct($srcImage, array $config = [])
    {

        /**
         * 图片类型
         */
        if (isset($config['allowType'])) {
            $this->allowType = $config['allowType'];
        }
        $this->srcImage = $srcImage;

        if (file_exists($srcImage) === false) {
            throw new \Exception("源图不存在无法压缩图片！");
        }


        $imageInfo = $this->getImageInfo($this->srcImage);
        if (!$imageInfo) {
            throw new \Exception("获取图片信息失败！");
        }
        $this->srcImageMime = $imageInfo['mime'];

        if (in_array($this->srcImageMime, $this->allowType) === false) {
            throw new \Exception("图片格式不合法！无法压缩图片");
        }

        $this->imageObj = $this->createImage($this->srcImage, $this->srcImageMime);
        if (!$this->imageObj) {
            throw new \Exception("创建图片对象失败！");
        }
        $this->srcImageWidth = $this->distImageWidth = imagesx($this->imageObj);
        $this->srcImageHeight = $this->distImageHeight = imagesy($this->imageObj);
        $this->srcImageOrientation = $this->getOrientation();
    }

    /**
     * 调整大小
     * @param $width
     * @param $height
     * @return $this
     */
    public function resizeTo($width, $height)
    {
        if ($width) {
            $this->distImageWidth = $width;
        } else {
            $this->distImageWidth = $this->getWidth();
        }
        if ($height) {
            $this->distImageHeight = $height;
        } else {
            $this->distImageHeight = $this->getHeight();
        }
        return $this;
    }


    /**
     * 设置图片压缩
     * @param float $quality
     * @throws \Exception
     */
    public function qualityTo($quality = 0.8)
    {
        //判断是否加载了该扩展
        if(!extension_loaded('Imagick')){
            throw new \Exception("对不起，图片压缩功能需要加载Imagick");
        }
        $this->quality = $quality;
    }

    /**
     * 按比例缩小
     * @param $ratio
     * @return ImageClipper
     */
    public function  scaleTo($ratio)
    {
        return $this->resizeTo(
            $ratio * $this->getWidth(),
            $ratio * $this->getHeight()
        );
    }

    /**
     * 获得mime
     * @return mixed
     */
    public function getMime()
    {
        return $this->srcImageMime;
    }

    /**
     * 修复图片倾斜
     * @param $path
     * @return bool
     */
    public function fixORI($path = "")
    {
        $path = $path ? $path : $this->srcImage;
        $fixed = false;
        if ($this->srcImageOrientation !== false) {
            $fixed = $this->fixOrientation($this->srcImageOrientation);
        }
        if ($this->srcImage === $path && $fixed == false) {
            return false;
        }
        return ($this->save($this->imageObj, $path) && $fixed);
    }

    /**
     * 获得原始图宽度
     * @return mixed
     */
    public function getWidth()
    {
        return $this->srcImageWidth;
    }

    /**
     * 获得原始图高度
     * @return mixed
     */
    public function getHeight()
    {
        return $this->srcImageHeight;
    }

    /**
     * 获得图片类型
     * @return string
     */
    public function  getExtension()
    {
        $result = explode('/', $this->srcImageMime);
        $extension = isset($result[1]) ? $result[1] : '';
        return ($extension === 'jpeg') ? 'jpg' : $extension;
    }

    /**
     * 图片信息
     * @param $path
     * @return array|bool
     */
    protected function getImageInfo($path)
    {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return false;
        }
        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime' => $imageInfo['mime'],
        ];
    }

    /**
     * 是否支持EXIF
     * @param $mime
     * @return bool
     */
    private function isSupportExif($mime)
    {
        if (in_array($mime, ['image/jpeg', 'image/tiff', 'image/x-canon-cr2', 'image/x-nikon-nef'])) {
            return true;
        }
        return false;

    }

    /**
     * 修复图片
     * @param $orientation
     * @return bool
     */
    protected function fixOrientation($orientation)
    {
        if (!empty($orientation)) {
            switch ($orientation) {
                case 8:
                    $this->imageObj = imagerotate($this->imageObj, 90, 0);
                    break;
                case 3:
                    $this->imageObj = imagerotate($this->imageObj, 180, 0);;
                    break;
                case 6:
                    $this->imageObj = imagerotate($this->imageObj, -90, 0);
                    break;
            }
            if (in_array($orientation, [3, 6, 8])) {
                $this->srcImageWidth = imagesx($this->imageObj);
                $this->srcImageHeight = imagesy($this->imageObj);
                $this->srcImageOrientation = 1;
            }
            return true;
        }
        return false;

    }


    /**
     * 获得图片旋转信息
     * @return string
     */
    protected function getOrientation()
    {
        if ($this->isSupportExif($this->srcImageMime)) {
            $exif = @exif_read_data($this->srcImage);
            if ($exif !== false && isset($exif['Orientation'])) {
                return $exif['Orientation'];
            }
        }
        return false;
    }

    /**
     * 创建图片对象
     * @param $path
     * @param $mime
     * @return null|resource
     */
    protected function createImage($path, $mime)
    {

        $image = null;
        switch ($mime) {
            case 'image/jpeg':
                $image = ImageCreateFromJpeg($path);
                break;
            case 'image/png':
                $image = ImageCreateFromPNG($path);
                break;
        }
        return $image;
    }


    /**
     * 获取长宽比
     * @param $width
     * @param $height
     * @return float
     */
    protected function getAspectRatio($width, $height)
    {
        return round($width / $height, 4);
    }

    /**
     * 创建一个画布
     * @param $width
     * @param $height
     * @return resource
     */
    protected function createCanvas($width, $height)
    {
        if (function_exists("imagecreatetruecolor")) {
            return imagecreatetruecolor($width, $height);
        } else {
            return imagecreate($width, $height);
        }

    }

    /**
     * 保存图片
     * @param $imageObj
     * @param $path
     * @return bool
     */
    protected function save($imageObj, $path)
    {
        if (function_exists('imagejpeg')) {
            $result = ImageJpeg($imageObj, $path);
        } else {
            $result = ImagePNG($imageObj, $path);
        }
        return $result;
    }


    /**
     * 执行
     * @param $distFile
     * @param bool|true $fixORI
     * @return bool
     */
    public function execute($distFile="", $fixORI = true)
    {
        $distFile = $distFile ? $distFile : $this->srcImage;
        if ($this->srcImageOrientation !== false && $fixORI === true) {
            $this->fixOrientation($this->srcImageOrientation);
        }
        $this->canvasObj = $this->createCanvas($this->distImageWidth, $this->distImageHeight);
        $benchMarkRatio = $this->getAspectRatio($this->distImageWidth, $this->distImageHeight);
        $sourceImageRatio = $this->getAspectRatio($this->srcImageWidth, $this->srcImageHeight);
        if ($benchMarkRatio > $sourceImageRatio) {
            $width = $this->srcImageWidth;
            $height = ($this->srcImageWidth * $this->distImageHeight) / $this->distImageWidth;
            $x = 0;
            $y = intval(($this->srcImageHeight - $height) / 2);
        } else {
            $height = $this->srcImageHeight;
            $width = ($this->srcImageHeight * $this->distImageWidth) / $this->distImageHeight;
            $x = intval(($this->srcImageWidth - $width) / 2);
            $y = 0;
        }
        if (imagecopyresampled($this->canvasObj, $this->imageObj, 0, 0, $x, $y, $this->distImageWidth,
                $this->distImageHeight, $width, $height) === false
        ) {
            return false;
        }
        if ($this->save($this->canvasObj, $distFile) === false) {
            return false;
        }
        if($this->quality>0 && $this->quality<1){
            $imagick = new \Imagick();
            $imagick->readImage($distFile);
            $imagick->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($this->quality);
            if(!$imagick->writeImage($distFile))
            {
                return false;
            }
            $imagick->clear();
            $imagick->destroy();
        }

        return true;

    }


    /**
     * 注销
     */
    public function __destruct()
    {
        if ($this->imageObj) {
            ImageDestroy($this->imageObj);
        }

        if ($this->canvasObj) {
            ImageDestroy($this->canvasObj);
        }

    }

}