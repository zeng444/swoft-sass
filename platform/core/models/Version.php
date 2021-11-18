<?php

namespace Application\Core\Models;

use Phalcon\Di;
use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Validation\Validator\InclusionIn as InclusionIn;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

class Version extends BaseModel
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
    public $isAvailable = 1;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=true)
     */
    public $version;

    /**
     *
     * @var string
     * @Column(type="string", length=800, nullable=true)
     */
    public $summary;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $patchUrl;

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
     * @return bool
     * @author Robert
     */
    public function beforeValidation(): bool
    {
        $this->isAvailable = intval($this->isAvailable);
        return true;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            ["version", "summary", "patchUrl"],
            new StringLength(
                [
                    "model" => $this,
                    "max" => [
                        "version" => 40,
                        "summary" => 800,
                        "patchUrl" => 255
                    ],
                    "messageMaximum" => [
                        "version" => "版本号格式错误，字符长度应该在1-40之间",
                        "summary" => "迭代内容格式错误，字符长度应该在1-800之间",
                        "patchUrl" => "升级包地址格式错误，字符长度应该在1-255之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["isAvailable"],
            new InclusionIn(
                [
                    "model" => $this,
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
        $this->setSchema("lightning_center");
        parent::initialize();
        $this->setSource("version");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'version';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Version[]|Version|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Version|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
