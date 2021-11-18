<?php

namespace Application\Core\Models;

use Phalcon\Validation;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;

/**
 *
 * @author Robert
 *
 * Class AdministratorRole
 *
 */
class AdministratorRole extends BaseModel
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
     * @Column(type="string", length=50, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=2000, nullable=true)
     */
    public $rule_map;

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
     *
     */
    const SUPER_ADMIN_ROLE_ID = '1';

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema($this->getDi()->get('config')->database->dbname);
        parent::initialize();
        $this->setSource("administrator_role");
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
            ["name"],
            new PresenceOfValidator(
                [
                    "model"   => $this,
                    "message" => ":field不能为空",
                ]
            )
        );

        $validator->add(
            ["name","rule_map"],
            new StringLength(
                [
                    "model"   => $this,
                    "max" => [
                        "name" => 50,
                        "rule_map" => 2000
                    ],
                    "messageMaximum" => [
                        "name" => "角色名格式错误，字符长度应该在1-50之间",
                        "rule_map" => "规则数据格式错误，字符长度应该在1-2000之间"
                    ]
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'administrator_role';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdministratorRole[]|AdministratorRole|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     *
     * @author Robert
     *
     * @param null $parameters
     * @return AdministratorRole
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     *
     * @author Robert
     *
     * @param $roleId
     * @return bool|mixed
     */
    public static function getMyAcl($roleId)
    {
        $adminRole = self::findFirst($roleId);
        if (!$adminRole) {
            return [];
        }
        if (!$adminRole->rule_map) {
            return [];
        }
        return json_decode($adminRole->rule_map, true);
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if ($this->id == self::SUPER_ADMIN_ROLE_ID) {
            $this->appendMessage(new Message('角色' . $this->name . '是超级管理员组，禁止删除'));
            return false;
        }
        $administrator = Administrator::findFirst([
            'conditions' => 'role_id=:role_id: and is_deleted=:is_deleted:',
            'bind' => ['is_deleted' => '0', 'role_id' => $this->id]
        ]);
        if ($administrator) {
            $this->appendMessage(new Message('角色' . $this->name . '存在管理用户，请先删除相关管理员'));
            return false;
        }
        return true;
    }

    /**
     * 生成ACL
     * @author Robert
     *
     * @param $controllerPath
     * @return array
     * @throws ReflectionException
     */
    public static function getAclList($controllerPath)
    {
        $files = scandir($controllerPath);
        $data = [];
        $exceptFile = ['ControllerBase.php', 'IndexController.php', 'MenuController.php', 'MainController.php'];
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && !in_array($file, $exceptFile)) {
                $className = str_replace('.php', '', $file);
                if (!class_exists($className)) {
                    include_once($controllerPath . $file);
                }
                $class = new \ReflectionClass($className);
                $methods = $class->getMethods(\ReflectionProperty::IS_PUBLIC);
                $classAliasName = str_replace('Controller', '', $className);
                $data[$classAliasName] = [];
                $r = [];
                foreach ($methods as $method) {
                    if (strtolower($method->class) == strtolower($className)) {
                        $doc = $method->getDocComment();
                        $docParse = new \Application\Admin\Components\Doc\Parser();
                        $doc = $docParse->parse($doc);
                        $methodName = str_replace('Controller', '', $method->class) . '/' . str_replace('Action', '', $method->name);
                        $desc = isset($doc['long_description']) ? $doc['long_description'] : '';
                        if (!$desc) {
                            $desc = isset($doc['description']) ? $doc['description'] : '';
                        }
                        $r[$methodName] = $desc ? $desc : $methodName;
                    }
                }
                $data[$classAliasName]['name'] = current($r);
                $data[$classAliasName]['rule'] = $r;
            }
        }
        return $data;
    }

    /**
     *
     * @author Robert
     *
     * @param $roleId
     * @return bool
     */
    public static function isSuperAdminRole($roleId)
    {
        return $roleId == AdministratorRole::SUPER_ADMIN_ROLE_ID;
    }

}
