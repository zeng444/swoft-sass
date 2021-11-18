<?php
namespace Application\Admin\Components\Mvc;

/**
 *
 * @author Robert
 *
 * Class Dispatcher
 * @package Application\Admin\Components\Mvc
 *
 * ....................................................
 *                       _oo0oo_
 *                      o8888888o
 *                      88" . "88
 *                      (| -_- |)
 *                      0\  =  /0
 *                    ___/`---'\___
 *                  .' \\|     |// '.
 *                 / \\|||  :  |||// \
 *                / _||||| -卍-|||||- \
 *               |   | \\\  -  /// |   |
 *               | \_|  ''\---/''  |_/ |
 *               \  .-\__  '-'  ___/-. /
 *             ___'. .'  /--.--\  `. .'___
 *          ."" '<  `.___\_<|>_/___.' >' "".
 *         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *         \  \ `_.   \_ __\ /__ _/   .-` /  /
 *     =====`-.____`.___ \_____/___.-`___.-'=====
 *                       `=---='
 *
 *..............Stay hungry. Stay foolish..............
 *
 */
class Dispatcher extends \Phalcon\Mvc\Dispatcher
{

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getHandlerClass()
    {
        if (strpos($this->_handlerName, '_') === false) {
            return ucfirst($this->_handlerName) . $this->_handlerSuffix;
        }
        return parent::getHandlerClass();
    }

}
