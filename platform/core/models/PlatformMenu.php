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

class PlatformMenu extends BaseModel
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
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $link;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $icon;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    public $sort;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    public $parent_id;

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


    const SUPER_ADMIN = 'administrator';

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        $this->setSource("platform_menu");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'platform_menu';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PlatformMenu[]|PlatformMenu|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PlatformMenu|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     *  Before Validations and business logic
     *
     * @return boolean
     */
    public function beforeValidation()
    {
        if (strlen($this->sort) === 0) {
            $this->sort = 100;
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
            ["name"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field????????????",
                ]
            )
        );

        $validator->add(
            ["link","name","icon"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                        "link" => 255,
                        "name" => 100,
                        "icon" => 50
                    ],
                    "messageMaximum" => [
                        "link" => "link????????????????????????????????????1-255??????",
                        "name" => "name????????????????????????????????????1-100??????",
                        "icon" => "icon????????????????????????????????????1-50??????"
                    ]
                ]
            )
        );

        if ($this->sort) {
            $validator->add(
                ["sort"],
                new BetweenValidation(
                    [
                        "model" => $this,
                        "minimum" => [
                            "sort" => 0
                        ],
                        "maximum" => [
                            "sort" => 4294967295
                        ],
                        "message" => [
                            "sort" => "sort????????????????????????????????????0-4294967295??????"
                        ]
                    ]
                )
            );
        }
        if ($this->parent_id) {
            $validator->add(
                ["parent_id"],
                new BetweenValidation(
                    [
                        "model" => $this,
                        "minimum" => [
                            "parent_id" => 0
                        ],
                        "maximum" => [
                            "parent_id" => 4294967295
                        ],
                        "message" => [
                            "parent_id" => "parent_id????????????????????????????????????0-4294967295??????"
                        ]
                    ]
                )
            );
        }

        if ($this->sort) {
            $validator->add(
                ["sort"],
                new DigitValidator(
                    [
                        "model"   => $this,
                        "message" => [
                            "sort" => "sort????????????????????????????????????????????????"
                        ],
                    ]
                )
            );
        }
        if ($this->parent_id) {
            $validator->add(
                ["parent_id"],
                new DigitValidator(
                    [
                        "model"   => $this,
                        "message" => [
                            "parent_id" => "parent_id????????????????????????????????????????????????"
                        ],
                    ]
                )
            );
        }

        return $this->validate($validator);
    }

    /**
     * Author:Robert
     *
     * @param $val
     * @return string
     */
    public static function formatRouteUrl($val)
    {
        $val = strtolower(preg_replace('/^\//', '', $val));
        $str = explode('/', $val);
        if (!isset($str[1]) || !$str[1]) {
            $val = strpos($val, '/') === false ? $val . '/index' : $val . 'index';
        }
        return $val;
    }


    /**
     *
     * @author Robert
     *
     * @param $adminId
     * @return array
     */
    public static function menuList($adminId)
    {
        $admin = Administrator::findFirst($adminId);
        $roles = AdministratorRole::getMyAcl($admin->role_id);
        $roles = array_map(function ($val) {
            return self::formatRouteUrl($val);
        }, $roles);

        $platformMenus = self::find([
            'conditions' => 'parent_id is null',
            'order' => 'sort asc, id desc'
        ]);
        $data = [];
        foreach ($platformMenus as $key => $platformMenu) {
            $childPlatformMenus = self::find([
                'conditions' => 'parent_id=:parent_id:',
                'bind' => [
                    'parent_id' => $platformMenu->id
                ],
                'order' => 'sort asc, id desc'
            ]);
            $childrenData = [];
            foreach ($childPlatformMenus as $childPlatformMenu) {
                if (in_array(strtolower(preg_replace('/^\//', '', $childPlatformMenu->link)), $roles) || $admin->role_id === AdministratorRole::SUPER_ADMIN_ROLE_ID) {
                    $childPlatformMenu = $childPlatformMenu->toArray(['id', 'name', 'link', 'icon']);
                    $childPlatformMenu['link'] = '/'.preg_replace('/^\//', '',  $childPlatformMenu['link']);
                    array_push($childrenData, $childPlatformMenu);
                }
            }
            if ($childrenData || $platformMenu->link) {
                if ($platformMenu->link) {
                    $val = self::formatRouteUrl($platformMenu->link);
                    if (in_array($val, $roles) || $admin->role_id === AdministratorRole::SUPER_ADMIN_ROLE_ID) {
                        $data[$key] = $platformMenu->toArray(['id', 'name', 'link', 'icon']);
                        $data[$key]['children'] = $childrenData;
                    }
                } else {
                    $platformMenu =  $platformMenu->toArray(['id', 'name', 'link', 'icon']);
                    $platformMenu['link'] =  '/'.preg_replace('/^\//', '',  $platformMenu['link']);;
                    $data[$key] = $platformMenu;
                    $data[$key]['children'] = $childrenData;
                }
            }
        }

        return $data;
    }

}
