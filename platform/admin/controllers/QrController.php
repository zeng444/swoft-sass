<?php
use Phalcon\Mvc\View;
use Application\Admin\Components\Web\Auth\UserIdentity as UserIdentity;
use Application\Core\Models\Administrator;

/**
 * Author:Robert
 *
 * Class QrController
 */
class QrController extends ControllerBase
{


    /**
     * Author:Robert
     *  微信扫码绑定
     */
    public function bindAction()
    {
        if ($this->request->isPost()) {
            $clientAddress = $this->request->getClientAddress(true);
            if ($clientAddress !== '121.41.21.104') {
                $this->apiError('不可接受的授权服务IP' . $clientAddress);
            }
            $account = trim($this->request->get('account'));
            $account = $account ? $account:"administrator";
            $openid = trim($this->request->get('openid'));
            $password = $this->request->get('password');
            if (!$openid) {
                $this->apiError('openid参数不能为空');
            }
            if (strlen($openid) != 28) {
                $this->apiError('openid格式不合法');
            }
            $administrator = Administrator::findFirst([
                'conditions' => 'name=:name: and is_deleted=:is_deleted:',
                'bind' => [
                    'name' => $account,
                    'is_deleted' => '0'
                ]
            ]);
            if (!$administrator) {
                $this->apiError('管理员不存在');
            }
            if($administrator->wechat_openid){
                $this->apiError('该账户已经绑定，不能重复绑定');
            }
            if ($administrator->auth($password) === false) {
                $this->apiError('您的密码错误，绑定管理员失败');
            }
            $administrator->wechat_openid = $openid;
            if ($administrator->save() === false) {
                $this->apiError($administrator->getFirstError());
            }
            $this->apiSuccess([
                'data' => 'OK'
            ]);

        }
    }

    /**
     * Author:Robert
     * 微信扫码认证
     */
    public function verifyAction()
    {

        if ($this->request->isPost()) {
            $clientAddress = $this->request->getClientAddress(true);
            if ($clientAddress !== '121.41.21.104') {
                $this->apiError('不可接受的授权服务IP' . $clientAddress);
            }
            $openid = trim($this->request->get('openid'));
            $token = trim($this->request->get('token'));
            if (!$openid) {
                $this->apiError('openid参数不能为空');
            }
            if (strlen($openid) != 28) {
                $this->apiError('openid格式不合法');
            }
            $administrator = Administrator::findFirst([
                'conditions' => 'wechat_openid=:wechat_openid: and is_deleted=:is_deleted:',
                'bind' => [
                    'wechat_openid' => $openid,
                    'is_deleted' => '0'
                ]
            ]);
            if (!$administrator) {
                $this->apiError('平台暂未绑定微信登录', 5001);
            }
            if($administrator->isBlock()){
                $this->apiError('您的账户锁定中，无法登录');
            }
            $userIdentity = new UserIdentity($administrator->name, '');
            $userIdentity->id = $administrator->id;
            $userIdentity->name = $administrator->name;
            $user = $this->getDi()->get('user');
            if ($user->login($userIdentity) === false) {
                $this->apiError($user->getErrorMessage());
            }
            $this->apiSuccess([
                'data' =>[
                    'uid' => $token,
                    'openid' => $openid,
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'access_token' => $user->generateAccessToken()
                ]
            ]);
        }
    }

}
