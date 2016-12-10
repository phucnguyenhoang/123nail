<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class BookingsController extends ApiController
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

        $data = $this->request->data;
        // verify service
        $serviceData = $this->request->data('services');
        if (empty($serviceData)) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }
        $serviceId = array();
        foreach ($serviceData as $row) {
            $serviceId[] = $row['id'];
        }
        $servicesTable = TableRegistry::get('Services');
        $query = $servicesTable->find()
            ->contain(['Categories'])
            ->where(['Categories.shops_id' => $permission->shops_id])
            ->andWhere(function ($exp, $q) use ($serviceId) {
                return $exp->in('Services.id', $serviceId);
            });
        if ($query->count() < sizeof($serviceId)) {
            $result = $this->_getResult('error', 400, $this->msg['service_not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        // verify customer data
        $customerData = $this->request->data('customers_id');
        $customersTable = TableRegistry::get('Customers');
        $customerId = null;
        if (is_array($customerData)) {
            $customerData['shops_id'] = $permission->shops_id;
            
            $customer = $customersTable->newEntity();
            $customer = $customersTable->patchEntity($customer, $customerData);
            if ($customersTable->save($customer)) {
                $customerId = $customer->id;
            } else {
                $result = $this->_getResult('failed', 400, $this->msg['create_failed'], $customer->errors());
                $this->_handleResponse($result);
                return;
            }            
        } else {
            $customerId = $customerData;
            $customer = $customersTable->find()->where(['id' => $customerId, 'shops_id' => $permission->shops_id]);
            if ($customer->count() <= 0) {
                $result = $this->_getResult('failed', 400, $this->msg['customer_not_found']);
                $this->_handleResponse($result);
                return;
            }
        }

        // create booking
        $bookingData = array(
            'customers_id' => $customerId,
            'date' => $this->request->data('date'),
            'start_time' => $this->request->data('start_time'),
            'end_time' => $this->request->data('end_time'),
            'note' => $this->request->data('note')
        );
        $bookingDate = new Time($bookingData['date'].$bookingData['start_time']);
        if ($bookingDate <= Time::now()) {
            $result = $this->_getResult('error', 400, $this->msg['booking_date_error']);
            $this->_handleResponse($result);
            return;
        }
        $bookingStart = new Time($bookingData['start_time']);
        $bookingEnd = new Time($bookingData['end_time']);
        if ($bookingStart <= Time::now() || $bookingStart >= $bookingEnd) {
            $result = $this->_getResult('error', 400, $this->msg['booking_time_error']);
            $this->_handleResponse($result);
            return;
        }
        $booking = $this->Bookings->newEntity();
        $booking = $this->Bookings->patchEntity($booking, $bookingData);
        $booking = $this->Bookings->save($booking);
        $bookingId = $booking->id;

        // create booking services
        $bookingServices = array();
        foreach ($serviceData as $row) {
            $tmp = array(
                'bookings_id' => $bookingId,
                'services_id' => $row['id'],
                'note' => $row['note']
            );
            $bookingServices[] = $tmp;
        }
        
        $bookingsHasServicesTable = TableRegistry::get('BookingsHasServices');
        $bookingsHasServicesTable->addMultiple($bookingServices);

        $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $bookingId]);
        $this->_handleResponse($result);
        return;
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