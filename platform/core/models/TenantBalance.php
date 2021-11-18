<?php

namespace Application\Core\Models;

use Phalcon\Di;
use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionIn;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Between as BetweenValidation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

class TenantBalance extends BaseModel
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
     * @Column(type="integer", length=10, nullable=false)
     */
    public $tenantId;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $outTradeNo;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $transactionId;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $biz;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $bizData;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $fee;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    public $remark;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $paidAt;

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
    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            ["tenantId","outTradeNo","transactionId","biz","fee"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["outTradeNo","transactionId","bizData","remark"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                         "outTradeNo" => 40,
                         "transactionId" => 40,
                         "bizData" => 200,
                         "remark" => 200
                    ],
                    "messageMaximum" => [
                         "outTradeNo" => "本地订单号格式错误，字符长度应该在1-40之间",
                         "transactionId" => "支付渠道订单号格式错误，字符长度应该在1-40之间",
                         "bizData" => "业务数据格式错误，字符长度应该在1-200之间",
                         "remark" => "业务备注格式错误，字符长度应该在1-200之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["tenantId","fee"],
            new BetweenValidation(
                [
                    "model" => $this,
                    "minimum" => [
                        "tenantId" => 0,
                         "fee" => 0
                    ],
                    "maximum" => [
                        "tenantId" => 4294967295,
                         "fee" => 4294967295
                    ],
                    "message" => [
                        "tenantId" => "租客Id格式错误，字符范围应该在0-4294967295之间",
                         "fee" => "金额格式错误，字符范围应该在0-4294967295之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["tenantId","fee"],
            new DigitValidator(
                [
                    "model"   => $this,
                    "message" => [
                         "tenantId" => ":field格式不合法，应该为整数类型的字符",
                         "fee" => ":field格式不合法，应该为整数类型的字符"
                    ],
                ]
            )
        );

        $validator->add(
            ["biz"],
            new InclusionIn(
                [
                    "model"   => $this,
                    "message" => [
                         "biz" => ":field格式不合法，非指定范围内的字符"
                    ],
                    "domain" => [
                         "biz" => ["SMS","LICENSE"]
                    ],
                ]
            )
        );

        if ($this->paidAt) {
            $validator->add(
                ["paidAt"],
                new DateValidator(
                    [
                        "model"   => $this,
                        "message" => [
                            "paidAt" => "支付时间错误的日期时间格式，请使用Y-m-d H:i:s的格式"
                        ],
                        "format" => [
                            "paidAt" =>"Y-m-d H:i:s",
                        ],
                    ]
                )
            );
        }

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("tenant_balance");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tenant_balance';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TenantBalance[]|TenantBalance|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TenantBalance|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
