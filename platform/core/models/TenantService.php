<?php

namespace Application\Core\Models;

use Phalcon\Di;
use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Between as BetweenValidation;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

class TenantService extends BaseModel
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
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $serverId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=200, nullable=false)
     */
    public $serviceId;

    /**
     *
     * @var integer
     * @Column(type="integer", length=200, nullable=false)
     */
    public $databaseId;

    /**
     *
     * @var string
     * @Column(type="string", length=80, nullable=true)
     */
    public $dbName;

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
            ["tenantId","serviceId"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["dbName"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                         "dbName" => 80
                    ],
                    "messageMaximum" => [
                         "dbName" => "数据库格式错误，字符长度应该在1-80之间"
                    ]
                ]
            )
        );

        if ($this->serverId) {
            $validator->add(
                ["serverId"],
                new BetweenValidation(
                    [
                        "model" => $this,
                        "minimum" => [
                            "serverId" => -2147483648
                        ],
                        "maximum" => [
                            "serverId" => 2147483647
                        ],
                        "message" => [
                            "serverId" => "服务器ID格式错误，字符范围应该在-2147483648-2147483647之间"
                        ]
                    ]
                )
            );
        }
        $validator->add(
            ["tenantId","serviceId", "databaseId"],
            new BetweenValidation(
                [
                    "model" => $this,
                    "minimum" => [
                        "tenantId" => 0,
                        "serviceId" => 0,
                        "databaseId" => 0
                    ],
                    "maximum" => [
                        "tenantId" => 4294967295,
                         "serviceId" => 4294967295,
                         "databaseId" => 4294967295
                    ],
                    "message" => [
                        "tenantId" => "租户Id格式错误，字符范围应该在0-4294967295之间",
                         "serviceId" => "服务ID格式错误，字符范围应该在0-4294967295之间",
                         "databaseId" => "数据库ID格式错误，字符范围应该在0-4294967295之间"
                    ]
                ]
            )
        );

        if ($this->serverId) {
            $validator->add(
                ["serverId"],
                new DigitValidator(
                    [
                        "model"   => $this,
                        "message" => [
                             "serverId" => "服务器ID格式不合法，应该为整数类型的字符"
                        ],
                    ]
                )
            );
        }
        $validator->add(
            ["tenantId","serviceId"],
            new DigitValidator(
                [
                    "model"   => $this,
                    "message" => [
                         "tenantId" => ":field格式不合法，应该为整数类型的字符",
                         "serviceId" => ":field格式不合法，应该为整数类型的字符"
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
        $this->setSource("tenant_service");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tenant_service';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TenantService[]|TenantService|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TenantService|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param int $tenantId
     * @return int|null
     * @author Robert
     */
    public static function getTenantDatabaseId(int $tenantId): ?int
    {
        $tenantService = TenantService::findFirst([
            'conditions' => 'tenantId = :tenantId:',
            'bind' => [
                'tenantId' => $tenantId
            ]
        ]);

        if (!$tenantService) {
            return null;
        }
        $serviceDatabase = ServiceDatabase::findFirst([
            'conditions' => 'serviceId = :serviceId: AND database = :database: ',
            'bind' => [
                'serviceId' => $tenantService->serviceId,
                'database' => $tenantService->dbName,
            ]
        ]);
        return $serviceDatabase ? $serviceDatabase->id : null;
    }

}
