<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class CustomersController extends ApiController
{
    public function index()
    {     
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $this->paginate = [
            'order' => ['Customers.first_name' => 'asc']
        ];
        $customers = $this->paginate($this->Customers->find()->where(['shops_id' => $permission->shops_id]));

        $this->set(compact('customers'));
        $this->set('_serialize', 'customers');
    }

    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $customer = $this->Customers->find()->where(['id' => $id, 'shops_id' => $permission->shops_id]);

        if ($customer->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $this->set('customer', $customer);
        $this->set('_serialize', 'customer');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }
        
        $customer = $this->Customers->newEntity();
        $data = $this->request->data;
        $data['shops_id'] = $permission->shops_id;
        $customer = $this->Customers->patchEntity($customer, $data);

        if ($this->Customers->save($customer)) {
            $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $customer->id]);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['create_failed'], $customer->errors());
        }

        $this->_handleResponse($result);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $customer = $this->Customers->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($customer)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $customer = $this->Customers->patchEntity($customer, $this->request->data);

        if ($this->Customers->save($customer)) {
            $result = $this->_getResult('success', 200, $this->msg['edit_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['edit_failed'], $customer->errors());
        }

        $this->_handleResponse($result);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $customer = $this->Customers->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($customer)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        if ($this->Customers->delete($customer)) {
            $result = $this->_getResult('success', 200, $this->msg['delete_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['delete_failed'], $customer->errors());
        }

        $this->_handleResponse($result);
    }
}
