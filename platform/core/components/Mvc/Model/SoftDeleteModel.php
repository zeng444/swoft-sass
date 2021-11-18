<?php
namespace Application\Core\Components\Mvc\Model;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 *
 * @author Robert
 *
 * Class ErrorManager
 * @package Application\Core\Components\Mvc\Model
 *
 */
trait SoftDeleteModel
{

    /**
     *
     * @author Robert
     *
     */
    protected function addSoftDeleteBehavior()
    {
        if ($this->isSoftDelete() === true) {
            $this->addBehavior(
                new SoftDelete(
                    array(
                        'field' => 'is_deleted',
                        'value' => 1
                    )
                )
            );
        }
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    protected function isSoftDelete()
    {
        if (!property_exists($this, "is_deleted")) {
            return false;
        }
        return true;
    }

    /**
     * @param null $parameters
     * @return mixed
     */
    public static function find($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::find($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return mixed
     */
    public static function findFirst($parameters = null)
    {
        if (!$parameters) {
            return false;
        }
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::findFirst($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::findFirst($parameters);
    }

    /**
     * Author:Robert
     *
     * @param null $parameters
     * @return bool
     */
    public static function count($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::count($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::count($parameters);
    }

    /**
     * Author:Robert
     *
     * @param null $parameters
     * @return bool
     */
    public static function sum($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::sum($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::sum($parameters);
    }

    /**
     * Author:Robert
     *
     * @param null $parameters
     * @return bool
     */
    public static function average($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::average($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::average($parameters);
    }

    /**
     * Author:Robert
     *
     * @param null $parameters
     * @return bool
     */
    public static function maximum($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::maximum($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::maximum($parameters);
    }

    /**
     * Author:Robert
     *
     * @param null $parameters
     * @return bool
     */
    public static function minimum($parameters = null)
    {
        $called = get_called_class();
        if (!property_exists($called, 'is_deleted')) {
            return parent::minimum($parameters);
        }
        $parameters = self::addDeleteCriteria($parameters);
        return parent::minimum($parameters);
    }


    /**
     *
     * @author Robert
     *
     * @param bool $success
     * @param bool $exists
     * @return bool
     */
    protected function _postSave($success, $exists)
    {
        if ($success) {
            if ($exists) {
                if ($this->isSoftDelete()===false) {
                    $this->fireEvent("afterUpdate");
                }
            } else {
                $this->fireEvent("afterCreate");
            }
        }
        return $success;
    }

    /**
     *
     * @author Robert
     *
     * @param $parameters
     * @return array|string
     */
    public static function addDeleteCriteria($parameters)
    {
        if (is_array($parameters)) {
            if (isset($parameters['conditions'])) {
                if (false === strpos($parameters['conditions'], 'is_deleted')) {
                    $parameters['conditions'] .= ' AND is_deleted=0';
                }
            } else {
                $parameters['conditions'] = 'is_deleted=0';
            }
        } else {
            if (!$parameters) {
                $parameters = 'is_deleted=0';
            } else {
                if (preg_match('/^\d+$/', $parameters)) {
                    $parameters = "id=$parameters";
                }
                if (false === strpos($parameters, 'is_deleted')) {
                    if ($parameters) {
                        $parameters = "$parameters AND is_deleted=0";
                    } else {
                        $parameters = "is_deleted=0";
                    }
                }
            }
        }
        return $parameters;
    }

}