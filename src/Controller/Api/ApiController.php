<?php
namespace App\Controller\Api;

use Cake\Network\Request;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ApiController extends Controller
{
    use \Crud\Controller\ControllerTrait;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]);

        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'userModel' => 'ApiUsers',
                    'scope' => ['ApiUsers.active' => 1]
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'ApiUsers',
                    'scope' => ['ApiUsers.active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
    }

    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function checkPermission($checkUser = null)
    {
        $udid = $this->request->query('udid');
        if (empty($udid)) {
            return $this->_getResult('failed', 400, $this->msg['missing_udid']);
        }

        // verify correct udid value
        $shopSessions = TableRegistry::get('ShopSessions');
        $shop = $shopSessions->find()
                ->where(['udid' => $udid])
                ->where(function ($exp, $q) {
                    return $exp->isNull('logout_date');
                })
                ->first();
        if (is_null($shop)) {
            return $this->_getResult('failed', 400, $this->msg['invalid_udid']);
        }

        // verify correct user permission
        if ($checkUser === true) {
            $userSessions = TableRegistry::get('UserSessions');
            $session = $userSessions->find()
                    ->where(['udid' => $udid])
                    ->where(function ($exp, $q) {
                        return $exp->isNull('logout_date');
                    })
                    ->first();
            if (is_null($session)) {
                return $this->_getResult('failed', 403, $this->msg['permission_denied']);
            }
        }        

        return $shop;
    }

    protected $msg = array(
        'missing_udid' => 'Missing UDID.',
        'invalid_udid' => 'UDID incorrect.',
        'missing_parameter' => 'Missing parameter.',
        'login_failed' => 'Account or password not match.',
        'login_success' => 'Login successfully.',
        'logout_success' => 'Logout successfully.',
        'permission_denied' => 'Permission denied.',
        'not_found' => 'Request cant not found.',
        'create_success' => 'The field has created.',
        'create_failed' => 'The field can not create.',
        'edit_success' => 'The field has edited.',
        'edit_failed' => 'The field can not edit.',
        'delete_success' => 'The field has deleted.',
        'delete_failed' => 'The field can not delete.',
        'category_not_found' => 'Category id does not exist.',
        'customer_not_found' => 'Customer id does not exist.',
        'service_not_found' => 'Servce id does not exist.',
        'employee_not_found' => 'Employee id does not exist.',
        'bill_not_found' => 'Bill id does not exist.',
        'bill_not_accept_service' => 'This bill has been done.',
        'bill_service_not_found' => 'Bill service id does not exist.',
        'salary_date_error' => 'Salary date error.'
    );

    protected function _getResult($status, $code, $msg, $data = null) 
    {
        $result = array(
            'status' => $status,
            'code' => $code,
            'message' => $msg
        );
        if (!empty($data)) $result['data'] = $data;

        return $result;
    }

    protected function _handleResponse($result)
    {
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
