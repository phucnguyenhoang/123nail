<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class EmployeesController extends ApiController
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
            'order' => ['Employees.first_name' => 'asc']
        ];

        $isFree = $this->request->query('is_free');
        $conditions = array(
            'shops_id' => $permission->shops_id
        );

        if (!is_null($isFree)) {
            $conditions['is_free'] = $isFree;
        }

        $employees = $this->paginate($this->Employees->find()->where($conditions));

        $this->set(compact('employees'));
        $this->set('_serialize', 'employees');
    }

    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $employee = $this->Employees->find()->where(['id' => $id, 'shops_id' => $permission->shops_id]);

        if ($employee->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $this->set('employee', $employee);
        $this->set('_serialize', 'employee');
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
        
        $employee = $this->Employees->newEntity();
        $data = $this->request->data;
        $data['shops_id'] = $permission->shops_id;
        $employee = $this->Employees->patchEntity($employee, $data);

        if ($this->Employees->save($employee)) {
            $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $employee->id]);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['create_failed'], $employee->errors());
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

        $employee = $this->Employees->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($employee)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $employee = $this->Employees->patchEntity($employee, $this->request->data);

        if ($this->Employees->save($employee)) {
            $result = $this->_getResult('success', 200, $this->msg['edit_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['edit_failed'], $employee->errors());
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

        $employee = $this->Employees->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($employee)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        if ($this->Employees->delete($employee)) {
            $result = $this->_getResult('success', 200, $this->msg['delete_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['delete_failed'], $employee->errors());
        }

        $this->_handleResponse($result);
    }
}
