<?php
use Phalcon\Mvc\Controller;
use Application\Admin\Components\Api\Response as Response;
use Application\Core\Models\Administrator;
use Application\Core\Models\AdministratorRole;

class ControllerBase extends Controller
{

    /**
     * @var
     */
    protected $adminInfo;

    /**
     * @var array
     */
    protected $aclIgnoredController = ['menu', 'main', 'index'];

    /**
     *
     * @author Robert
     *
     * @param $dispatcher
     * @return bool|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function beforeExecuteRoute($dispatcher)
    {
        $user = $this->getDI()->get('user');
        if ($user->getId()) {
            $this->view->adminInfo = $this->adminInfo = Administrator::findFirst($user->getId());
            if (!$this->adminInfo) {
                $user->logout();
                return $this->response->redirect('/');
            }
            if ($this->aclControl($dispatcher) === false) {
                return $this->response->redirect('/');
            }
        } else {
            $controllerName = $dispatcher->getControllerName();
            if ($controllerName != 'index') {
                if ($this->request->isAjax()) {
                    $this->apiError($user->getErrorMessage(), $user->getErrorCode());
                } else {
                    return $this->response->redirect('/');
                }
            }
        }
    }

    /**
     *
     * @author Robert
     *
     * @param $dispatcher
     * @return bool
     */
    protected function aclControl($dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();
        $routeName = strtolower($controllerName . '/' . $actionName);
        if ($this->adminInfo->role_id !== AdministratorRole::SUPER_ADMIN_ROLE_ID && $this->adminInfo->isAclAllowed($routeName) == false && !in_array($controllerName, $this->aclIgnoredController)) {
            return false;
        }
        return true;
    }

    /**
     *
     * @author Robert
     *
     * @param $message
     * @param int $code
     * @param string $exception
     */
    public function apiError($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR_CODE, $exception = "")
    {
        echo json_encode($this->getDi()->get('controllerApiResponse')->setDebug(false)->error($message, $code, $exception));
        exit();
    }

    /**
     *
     * @author Robert
     *
     * @param $data
     * @param int $code
     * @param string $message
     */
    public function apiSuccess($data, $code = Response::HTTP_OK_CODE, $message = '')
    {
        echo json_encode($this->getDi()->get('controllerApiResponse')->setDebug(false)->success($data, $code, $message));
        exit();
    }


}
