<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ApiUsers Controller
 *
 * @property \App\Model\Table\ApiUsersTable $ApiUsers
 */
class ApiUsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $apiUsers = $this->paginate($this->ApiUsers);

        $this->set(compact('apiUsers'));
        $this->set('_serialize', ['apiUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Api User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $apiUser = $this->ApiUsers->get($id, [
            'contain' => []
        ]);

        $this->set('apiUser', $apiUser);
        $this->set('_serialize', ['apiUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $apiUser = $this->ApiUsers->newEntity();
        if ($this->request->is('post')) {
            $apiUser = $this->ApiUsers->patchEntity($apiUser, $this->request->data);
            if ($this->ApiUsers->save($apiUser)) {
                $this->Flash->success(__('The api user has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The api user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('apiUser'));
        $this->set('_serialize', ['apiUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Api User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $apiUser = $this->ApiUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $apiUser = $this->ApiUsers->patchEntity($apiUser, $this->request->data);
            if ($this->ApiUsers->save($apiUser)) {
                $this->Flash->success(__('The api user has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The api user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('apiUser'));
        $this->set('_serialize', ['apiUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Api User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $apiUser = $this->ApiUsers->get($id);
        if ($this->ApiUsers->delete($apiUser)) {
            $this->Flash->success(__('The api user has been deleted.'));
        } else {
            $this->Flash->error(__('The api user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
