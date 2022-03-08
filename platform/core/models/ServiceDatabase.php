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

class ServiceDatabase extends BaseModel
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
    public $serviceId;

    /**
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $serverId;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $database;


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
            ["serviceId","serverId","database"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["database"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                         "database" => 50
                    ],
                    "messageMaximum" => [
                         "database" => "数据库格式错误，字符长度应该在1-50之间"
                    ]
                ]
            )
        );

        $validator->add(
            ["serviceId","serverId"],
            new BetweenValidation(
                [
                    "model" => $this,
                    "minimum" => [
                        "serviceId" => 0,
                        "serverId" => 0,
                    ],
                    "maximum" => [
                        "serviceId" => 4294967295,
                        "serverId" => 4294967295,
                    ],
                    "message" => [
                        "serviceId" => "服务格式错误，字符范围应该在0-4294967295之间",
                        "serverId" => "服务器格式错误，字符范围应该在0-4294967295之间",
                    ]
                ]
            )
        );

        $validator->add(
            ["serviceId","serverId"],
            new DigitValidator(
                [
                    "model"   => $this,
                    "message" => [
                         "serviceId" => ":field格式不合法，应该为整数类型的字符"
                    ],
                ]
            )
        );
        if(!$this->id){
            $exist = self::findFirst([
                'conditions'=>'serviceId = :serviceId: AND database = :database:',
                'bind'=>[
                    'serviceId'=>$this->serverId,
                    'database'=>$this->database,
                ]
            ]);
            if($exist){
                $this->appendMessage(new Message('服务下重复的数据库'));
                return false;
            }
        }

        if(!$this->validate($validator)){
            return false;
        }
        if (!$this->id) {
            $exist = self::findFirst([
                'conditions' => 'serviceId = :serviceId: AND database = :database:',
                'bind' => [
                    'serviceId' => $this->serverId,
                    'database' => $this->database,
                ],
            ]);
            if ($exist) {
                $this->appendMessage(new Message('服务下重复的数据库'));
                return false;
            }
        } else {
            $exist = self::findFirst([
                'conditions' => 'serviceId = :serviceId: AND database = :database: AND id <> :id: ',
                'bind' => [
                    'id' => $this->id,
                    'serviceId' => $this->serverId,
                    'database' => $this->database,
                ],
            ]);
            if ($exist) {
                $this->appendMessage(new Message('服务下重复的数据库'));
                return false;
            }
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("service_database");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'service_database';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ServiceDatabase[]|ServiceDatabase|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ServiceDatabase|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Author:Robert
     *
     * @return int
     */
    public static function automaticSelectDb(): int
    {
        $sql = "SELECT  dbName,COUNT(*) AS `quantity` FROM `tenant_service` GROUP BY dbName ORDER BY quantity ASC limit 1";
        $self = new self();
        $db = $self->getWriteConnection();
        $result = $db->fetchOne($sql);
        if(!$result){
            return 0;
        }
        $serviceDatabase = ServiceDatabase::findFirst([
            'conditions'=>'database = :database:',
            'bind'=>[
                'database'=>$result['dbName']
            ]
        ]);
        if(!$serviceDatabase){
            return 0;
        }
        return $serviceDatabase->id;
    }
}
