<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class UsersController extends ApiController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $users = $this->Users->find('all');
        $this->set([
            'users' => $users,
            '_serialize' => ['users']
        ]);
    }

    public function login()
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $data = $this->request->data();
        $data['udid'] = $this->request->query('udid');
        // check required parameter
        if (empty($data['username']) || empty($data['password']) || empty($data['udid'])) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }
        $users = TableRegistry::get('Users');

        $verify = $users->verifyUserAccount($data['username'], $data['password']);
        if (!$verify) {
            $result = $this->_getResult('failed', 200, $this->msg['login_failed']);
            $this->_handleResponse($result);
            return;
        }

        $userSessions = TableRegistry::get('UserSessions');
        $userSessions->createSession($verify->id, $data['udid']);
        $result = $this->_getResult('success', 200, $this->msg['login_success'], $verify->toArray());
        $this->_handleResponse($result);
    }

    public function logout()
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }
        
        $udid = $this->request->query('udid');
        // check required parameter
        if (empty($udid)) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }
        
        $userSessions = TableRegistry::get('UserSessions');
        $session = $userSessions->find()->where(['udid' => $udid])->order(['login_date' => 'DESC'])->first();
        if (is_null($session)) {
            $result = $this->_getResult('failed', 400, $this->msg['invalid_udid']);
            $this->_handleResponse($result);
            return;
        }
        $session->logout_date = Time::now();
        $userSessions->save($session);

        $result = $this->_getResult('success', 200, $this->msg['logout_success']);
        $this->_handleResponse($result);
    }

}
