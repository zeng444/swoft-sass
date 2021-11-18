<?php

namespace Application\Core\Models;

use Phalcon\Di;
use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionIn;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Between as BetweenValidation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

class Service extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $isAvailable;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $serverId;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $code;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $tag;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $host;

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
     *  Before Validations and business logic
     *
     * @return boolean
     */
    public function beforeValidation()
    {
        if (!$this->isAvailable) {
            $this->isAvailable = '1';
        }

        return true;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            ["isAvailable","serverId"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["name","code","tag","host"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                         "name" => 50,
                         "code" => 50,
                         "tag" => 255,
                         "host" => 200
                    ],
                    "messageMaximum" => [
                         "name" => "服务名称格式错误，字符长度应该在1-50之间",
                         "code" => "服务代号格式错误，字符长度应该在1-50之间",
                         "tag" => "服务tag，逗号隔开格式错误，字符长度应该在1-255之间",
                         "host" => "服务地址格式错误，字符长度应该在1-200之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["serverId"],
            new BetweenValidation(
                [
                    "model" => $this,
                    "minimum" => [
                        "serverId" => 0
                    ],
                    "maximum" => [
                        "serverId" => 4294967295
                    ],
                    "message" => [
                        "serverId" => "服务器格式错误，字符范围应该在0-4294967295之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["serverId"],
            new DigitValidator(
                [
                    "model"   => $this,
                    "message" => [
                         "serverId" => ":field格式不合法，应该为整数类型的字符"
                    ],
                ]
            )
        );

        $validator->add(
            ["isAvailable"],
            new InclusionIn(
                [
                    "model"   => $this,
                    "message" => [
                         "isAvailable" => ":field格式不合法，应该为数字类型的字符"
                    ],
                    "domain" => [
                         "isAvailable" => [0, 1]
                    ],
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("service");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'service';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Service[]|Service|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Service|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
