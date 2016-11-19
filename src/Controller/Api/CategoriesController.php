<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class CategoriesController extends ApiController
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
            'order' => ['Categories.name' => 'asc']
        ];
        $categories = $this->paginate($this->Categories->find()->where(['shops_id' => $permission->shops_id]));

        $this->set(compact('categories'));
        $this->set('_serialize', 'categories');
    }

    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $category = $this->Categories->find()->where(['id' => $id, 'shops_id' => $permission->shops_id]);

        if ($category->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $this->set('category', $category);
        $this->set('_serialize', 'category');
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
        
        $category = $this->Categories->newEntity();
        $data = $this->request->data;
        $data['shops_id'] = $permission->shops_id;
        $category = $this->Categories->patchEntity($category, $data);

        if ($this->Categories->save($category)) {
            $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $category->id]);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['create_failed'], $category->errors());
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

        $category = $this->Categories->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($category)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $category = $this->Categories->patchEntity($category, $this->request->data);

        if ($this->Categories->save($category)) {
            $result = $this->_getResult('success', 200, $this->msg['edit_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['edit_failed'], $category->errors());
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

        $category = $this->Categories->find()->where(['id' => $id, 'shops_id' => $permission->shops_id])->first();
        if (is_null($category)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        if ($this->Categories->delete($category)) {
            $result = $this->_getResult('success', 200, $this->msg['delete_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['delete_failed'], $category->errors());
        }

        $this->_handleResponse($result);
    }
}
