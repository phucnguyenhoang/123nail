<?php
namespace App\Controller\Api;

use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ShopsController extends ApiController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $shops = $this->Shops->find('all');
        $this->set([
            'shops' => $shops,
            '_serialize' => ['shops']
        ]);
    }

    public function login()
    {        
        $data = $this->request->data();
        // check required parameter
        if (empty($data['account']) || empty($data['password']) || empty($data['udid'])) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }
        $shops = TableRegistry::get('Shops');

        $verify = $shops->verifyShopAccount($data['account'], $data['password']);
        if (!$verify) {
            $result = $this->_getResult('failed', 200, $this->msg['login_failed']);
            $this->_handleResponse($result);
            return;
        }
        
        $shopSessions = TableRegistry::get('ShopSessions');
        $shopSessions->createSession($verify, $data['udid']);
        $result = $this->_getResult('success', 200, $this->msg['login_success']);
        $this->_handleResponse($result);
    }

    public function logout()
    {
        $udid = $this->request->query('udid');
        // check required parameter
        if (empty($udid)) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }

        $shopSessions = TableRegistry::get('ShopSessions');
        $session = $shopSessions->find()->where(['udid' => $udid])->order(['login_date' => 'DESC'])->first();
        if (is_null($session)) {
            $result = $this->_getResult('failed', 400, $this->msg['invalid_udid']);
            $this->_handleResponse($result);
            return;
        }
        $session->logout_date = Time::now();
        $shopSessions->save($session);

        $result = $this->_getResult('success', 200, $this->msg['logout_success']);
        $this->_handleResponse($result);
    }

}
