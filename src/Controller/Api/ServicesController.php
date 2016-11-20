<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class ServicesController extends ApiController
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
            'contain' => ['Categories'],
            'order' => ['Services.name' => 'asc']
        ];

        $conditions = array(
            'Categories.shops_id' => $permission->shops_id
        );
        $categoryId = $this->request->query('categories_id');
        if (!empty($categoryId)) {
            $conditions['Services.categories_id'] = $categoryId;
        }
        $services = $this->paginate($this->Services->find()->where($conditions));

        $this->set(compact('services'));
        $this->set('_serialize', 'services');
    }

    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $service = $this->Services->find()->contain('Categories')->where(['Services.id' => $id, 'Categories.shops_id' => $permission->shops_id]);

        if ($service->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $this->set('service', $service);
        $this->set('_serialize', 'service');
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

        $categories = TableRegistry::get('Categories');
        $categoryId = $this->request->data('categories_id');
        $category = $categories->find()->where(['id' => $categoryId, 'shops_id' => $permission->shops_id]);
        if ($category->count() <= 0) {
            $result = $this->_getResult('failed', 400, $this->msg['category_not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $service = $this->Services->newEntity();
        $service = $this->Services->patchEntity($service, $this->request->data);

        if ($this->Services->save($service)) {
            $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $service->id]);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['create_failed'], $service->errors());
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
        if (!empty($this->request->data('categories_id'))) {
            $categories = TableRegistry::get('Categories');
            $categoryId = $this->request->data('categories_id');
            $category = $categories->find()->where(['id' => $categoryId, 'shops_id' => $permission->shops_id]);
            if ($category->count() <= 0) {
                $result = $this->_getResult('failed', 400, $this->msg['category_not_found']);
                $this->_handleResponse($result);
                return;
            }
        }

        $service = $this->Services->get($id);
        if (is_null($service)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $service = $this->Services->patchEntity($service, $this->request->data);

        if ($this->Services->save($service)) {
            $result = $this->_getResult('success', 200, $this->msg['edit_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['edit_failed'], $service->errors());
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

        $service = $this->Services->get($id);
        if (is_null($service)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        if ($this->Services->delete($service)) {
            $result = $this->_getResult('success', 200, $this->msg['delete_success']);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['delete_failed'], $service->errors());
        }

        $this->_handleResponse($result);
    }
}
