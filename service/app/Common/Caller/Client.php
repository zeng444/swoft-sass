<?php declare(strict_types=1);

namespace App\Common\Caller;

use RuntimeException;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Caller
 * @author Robert
 * @Bean()
 * @package App\Common\Caller
 */
class Client
{

    /**
     * @param string $className
     * @return mixed|object|null
     * @author Robert
     */
    private function getInstance(string $className)
    {
        if (\Swoft::hasBean($className)) {
            $class = \Swoft::getBean($className);
        } elseif (class_exists($className)) {
            $class = new $className;
        } else {
            $class = null;
        }
        return $class;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @return mixed
     * @author Robert
     */
    public static function call(string $className, string $methodName, array $params = [])
    {
        /** @var Client $sync */
        $sync = \Swoft::getBean(self::class);
        $model = $sync->getInstance($className);
        if (!$model) {
            throw new RuntimeException("className $className instance is not exits");
        }
        if (method_exists($model, $methodName) === false) {
            throw new RuntimeException("methodName $methodName is not exits");
        }
        return $model->$methodName(...$params);
    }
}
