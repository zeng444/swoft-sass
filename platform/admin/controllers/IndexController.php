<?php
use Phalcon\Mvc\View;
use Application\Admin\Components\Web\Auth\UserIdentity as UserIdentity;

/**
 *
 * @author Robert
 *
 * Class IndexController
 *
 */
class IndexController extends ControllerBase
{

    /**
     *
     * @author Robert
     *
     */
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $name = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');
            $userIdentity = new UserIdentity($name, $password);
            if ($userIdentity->authenticate() === false) {
                $this->apiError($userIdentity->getErrorMessage());
            }
            $user = $this->getDi()->get('user');
            if ($user->login($userIdentity) === false) {
                $this->apiError($user->getErrorMessage());
            }
            $data = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'access_token' => $user->getAccessToken(),
                'jump_url' => '/',
            ];
            $this->apiSuccess($data);
        }
        if (!$this->adminInfo) {
            $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        } else {
            $acl = $this->adminInfo->getDefaultAction(['main', 'index']);
            //登录后自动转向
            $this->dispatcher->forward([
                'controller' => $acl[0],
                'action' => $acl[1],
            ]);

        }
    }

    /**
     *
     * @author Robert
     *
     */
    public function show404Action()
    {
        if(!$this->getDI()->get('user')->getId()){
            $this->response->redirect('/');
        }
    }

}

