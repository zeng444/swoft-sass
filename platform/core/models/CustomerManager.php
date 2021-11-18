<?php

namespace Application\Core\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Between as BetweenValidation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

/**
 * Author:Robert
 *
 * Class CustomerManager
 * @package Application\Core\Models
 */
class CustomerManager extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=10, nullable=false)
     */
    public $tenantId;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=11, nullable=true)
     */
    public $mobile;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $createdAt;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $updatedAt;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation(): bool
    {
        $validator = new Validation();


        $validator->add(["tenantId"], new PresenceOfValidator([
            "model" => $this,
            "message" => ":field不能为空",
        ]));

        $validator->add(["name", "mobile"], new StringLength([
            "model" => $this,
            "max" => [
                "name" => 40,
                "mobile" => 11,
            ],
            "messageMaximum" => [
                "name" => "name格式错误，字符长度应该在1-40之间",
                "mobile" => "mobile格式错误，字符长度应该在1-11之间",
            ],
        ]));

        $validator->add(["tenantId"], new BetweenValidation([
            "model" => $this,
            "minimum" => [
                "tenantId" => 0,
            ],
            "maximum" => [
                "tenantId" => 4294967295,
            ],
            "message" => [
                "tenantId" => "tenantId格式错误，字符范围应该在0-4294967295之间",
            ],
        ]));

        $validator->add(["tenantId"], new DigitValidator([
            "model" => $this,
            "message" => [
                "tenantId" => ":field格式不合法，应该为整数类型的字符",
            ],
        ]));

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("customer_manager");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'customer_manager';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerManager[]|CustomerManager|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerManager|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
