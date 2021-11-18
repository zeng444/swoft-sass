<?php

namespace Application\Core\Models;


use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionIn;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;
use Application\Core\Components\Rpc\Client as RpcClient;

class Tenant extends BaseModel
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
     * @Column(type="string", length=30, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=false)
     */
    public $account;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=true)
     */
    public $province;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=true)
     */
    public $city;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $linkman;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $contact;

    /**
     * @var
     * @Column(type="string", length=50, nullable=true)
     */
    public $fullName;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $beginAt;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $endAt;

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
            ["isAvailable", "name","account"],
            new PresenceOfValidator(
                [
                    "model" => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["name", "account","fullName", "province", "city", "linkman", "contact"],
            new StringLength(
                [
                    "model" => $this,
                    "max" => [
                        "name" => 30,
                        "account" => 30,
                        "fullName" => 50,
                        "province" => 30,
                        "city" => 30,
                        "linkman" => 50,
                        "contact" => 20
                    ],
                    "messageMaximum" => [
                        "name" => "公司名称格式错误，字符长度应该在1-30之间",
                        "account" => "初始账号格式错误，字符长度应该在1-30之间",
                        "province" => "省份格式错误，字符长度应该在1-30之间",
                        "city" => "城市格式错误，字符长度应该在1-30之间",
                        "linkman" => "联系人格式错误，字符长度应该在1-50之间",
                        "contact" => "联系电话格式错误，字符长度应该在1-20之间"
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

        $validator->add(
            ["beginAt"],
            new DateValidator(
                [
                    "model" => $this,
                    "message" => [
                        "beginAt" => "有效期开始时间错误的日期时间格式，请使用Y-m-d H:i:s的格式"
                    ],
                    "format" => [
                        "beginAt" => "Y-m-d H:i:s",
                    ],
                ]
            )
        );

        $validator->add(
            ["endAt"],
            new DateValidator(
                [
                    "model" => $this,
                    "message" => [
                        "endAt" => "有效期结束时间错误的日期时间格式，请使用Y-m-d H:i:s的格式"
                    ],
                    "format" => [
                        "endAt" => "Y-m-d H:i:s",
                    ],
                ]
            )
        );
        if ($this->beginAt > $this->endAt) {
            $this->appendMessage(new Message('有效期开始时间不能大于结束时间'));
            return false;
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
        $this->setSource("tenant");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'tenant';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tenant[]|Tenant|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tenant|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param string $name
     * @param int $exceptionId
     * @return Tenant|\Phalcon\Mvc\Model\ResultInterface
     * @author Robert
     */
    public function hasRegistered(string $name, int $exceptionId)
    {
        $criteria = [
            'conditions' => 'name = :name:',
            'bind' => [
                'name' => $name
            ]
        ];
        if ($exceptionId) {
            $criteria['conditions'] .= ' AND id <> :id:';
            $criteria['bind']['id'] = $exceptionId;
        }
        return self::findFirst($criteria);
    }

    /**
     * @param bool $isAvailable
     * @param string $name
     * @param string $account
     * @param string $password
     * @param int $databaseId
     * @param string $province
     * @param string $city
     * @param string $linkman
     * @param string $contact
     * @param string $beginAt
     * @param string $endAt
     * @param array $config
     * @return bool
     * @author Robert
     */
    public function touch(bool $isAvailable, string $name, string $account,string $password, int $databaseId, string $province, string $city, string $linkman, string $contact, string $beginAt, string $endAt, array $config): bool
    {
        $isNewRecord = !$this->id;
        $exist = self::hasRegistered($name, $this->id ?: 0);
        if ($exist) {
            if ($this->id && $this->id === $exist->id) {
                $this->appendMessage(new Message('公司登录名称已经存在，无法注册'));
                return false;
            } else {
                $this->appendMessage(new Message('公司登录名称已经存在，无法注册'));
                return false;
            }
        }
        $this->name = $name;
        if($isNewRecord){
            $this->account = $account;
        }
        $this->isAvailable = intval($isAvailable);
        $this->province = $province;
        $this->city = $city;
        $this->linkman = $linkman;
        $this->contact = $contact;
        $this->beginAt = $beginAt;
        $this->endAt = $endAt;
        $db = $this->getWriteConnection();
        $db->begin();
        try {
            if (!$this->save()) {
                $db->rollback();
                return false;
            }
            if ($isNewRecord) {
                $serviceDatabase = ServiceDatabase::findFirst($databaseId);
                if (!$serviceDatabase) {
                    $db->rollback();
                    $this->appendMessage(new Message('数据库配置不存在'));
                    return false;
                }
                $service = Service::findFirst($serviceDatabase->serviceId);
                if (!$service) {
                    $db->rollback();
                    $this->appendMessage(new Message('服务配置不存在'));
                    return false;
                }
                $result = (new TenantService())->batchInsertAsDict([
                    'tenantId' => $this->id,
                    'serverId' => $service->serverId,
                    'serviceId' => $serviceDatabase->serviceId,
                    'dbName' => $serviceDatabase->database,
                    'createdAt' => $this->createdAt,
                    'updatedAt' => $this->createdAt,
                ], false, ['createdAt']);
                if (!$result) {
                    $db->rollback();
                    $this->appendMessage(new Message('配置租客数据库失败'));
                    return false;
                }
                $this->getDI()->get('rpc')->tenantDispatch((int)$this->id, \App\Rpc\Lib\Manager\UserInterface::class, 'registerSuper', [
                    (int)$this->id,
                    $account,
                    $password,
                    $this->contact,
                    '',
                    $config
                ]);
            }else{
                foreach ($config as $key => $val) {
                    $this->getDI()->get('rpc')
                         ->tenantDispatch((int)$this->id, \App\Rpc\Lib\Manager\SystemSettingInterface::class, 'set', [
                             (string)$key,
                             (string)$val,
                             (int)$this->id,
                         ]);
                }

            }
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollback();
            echo $e->getMessage().$e->getTraceAsString();
            $this->appendMessage(new Message('配置租客数据库失败'));
            return false;
        }

    }
}
