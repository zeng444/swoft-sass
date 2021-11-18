<?php

namespace Application\Core\Models;

use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation\Validator\InclusionIn as InclusionIn;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

/**
 *
 * @author Robert
 *
 * Class OperationLog
 *
 */
class OperationLog extends BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @var
     */
    public $event;

    /**
     *
     * @var string
     * @Column(type="string", length=15, nullable=false)
     */
    public $ip;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $action;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $data;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $administrator_id;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $administrator_name;

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


    const CREATE_EVENT = 'CREATE';
    const DELETE_EVENT = 'DELETE';
    const UPDATE_EVENT = 'UPDATE';

    public static $eventMap=[
        self::CREATE_EVENT=>'创建',
        self::DELETE_EVENT=>'删除',
        self::UPDATE_EVENT=>'更新',
    ];

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("operation_log");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'operation_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return OperationLog[]|OperationLog|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return OperationLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function validation()
    {

        $validator = new Validation();


        $validator->add(
            ["ip","data"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["ip","action","administrator_id","administrator_name"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                        "ip" => 15,
                        "action" => 255,
                        "administrator_id" => 50,
                        "administrator_name" => 50
                    ],
                    "messageMaximum" => [
                        "ip" => "IP地址格式错误，字符长度应该在1-15之间",
                        "action" => "行为格式错误，字符长度应该在1-255之间",
                        "administrator_id" => "操作人ID格式错误，字符长度应该在1-50之间",
                        "administrator_name" => "操作人姓名格式错误，字符长度应该在1-50之间"
                    ]
                ]
            )
        );

        if ($this->event) {
            $validator->add(
                ["event"],
                new InclusionIn(
                    [
                        "model"   => $this,
                        "message" => [
                            "event" => "event格式不合法，非指定范围内的字符"
                        ],
                        "domain" => [
                            "event" => ["CREATE","DELETE","UPDATE"],
                        ],
                    ]
                )
            );
        }

        return $this->validate($validator);
    }

}
