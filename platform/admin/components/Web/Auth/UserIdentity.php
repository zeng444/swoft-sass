<?php
namespace Application\Admin\Components\Web\Auth;

use Application\Admin\Components\ErrorManager;
use Application\Core\Models\Administrator;

/**
 *
 * @author Robert
 *
 * Class User
 * @package Application\App\Components\Web
 */
class UserIdentity
{

    use ErrorManager;

    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $id;

    /**
     *
     */
    const  USER_NOT_EXISTS_CODE = 404;

    /**
     *
     */
    const  ILLEGAL_PASSWORD_CODE = 401;

    /**
     * @param $name
     * @param $password
     */
    public function __construct($name, $password)
    {
        $this->name = $name;
        $this->password = $password;
    }

    /**
     *
     * @author Robert
     *
     * @return bool
     */
    public function authenticate()
    {
        $administrator = Administrator::getAdminByName($this->name);
        if (!$administrator) {
            $this->setError('你的用户名不存在', self::USER_NOT_EXISTS_CODE);
            return false;
        }
        if ($administrator->auth($this->password) !== true) {
            $this->setError($administrator->getFirstError(), self::ILLEGAL_PASSWORD_CODE);
            return false;
        }
        $this->id = $administrator->id;
        $this->name = $administrator->name;
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @author Robert
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
