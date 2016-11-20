<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 */
class ServicesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories']
        ];
        $services = $this->paginate($this->Services);

        $this->set(compact('services'));
        $this->set('_serialize', ['services']);
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Categories']
        ]);

        $this->set('service', $service);
        $this->set('_serialize', ['service']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $service = $this->Services->newEntity();
        if ($this->request->is('post')) {
            $service = $this->Services->patchEntity($service, $this->request->data);
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The service could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Services->Categories->find('list', ['limit' => 200]);
        $this->set(compact('service', 'categories'));
        $this->set('_serialize', ['service']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, $this->request->data);
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The service could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Services->Categories->find('list', ['limit' => 200]);
        $this->set(compact('service', 'categories'));
        $this->set('_serialize', ['service']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $service = $this->Services->get($id);
        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
