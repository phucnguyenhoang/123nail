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

        $fromDate = new Time($this->request->query('from'));
        $toDate = new Time($this->request->query('to'));
        if ($fromDate > $toDate) {
            $result = $this->_getResult('error', 400, $this->msg['booking_date_error']);
            $this->_handleResponse($result);
            return;
        }

        $query = $this->Bookings->find()
                    ->contain(['Customers', 'Services'])
                    ->where([
                            'Customers.shops_id' => $permission->shops_id,
                            'Bookings.date >=' => $fromDate,
                            'Bookings.date <=' => $toDate
                        ]);

        $bookings = array();
        if ($query->count() > 0) {
            foreach ($query as $row) {
                $tmp = array(
                    'id' => $row->id,
                    'date' => $row->date,
                    'start_time' => $row->start_time,
                    'end_time' => $row->end_time,
                    'status' => $row->status,
                    'note' => $row->note,
                    'customer' => [
                        'id' => $row->customer->id,
                        'first_name' => $row->customer->first_name,
                        'last_name' => $row->customer->last_name
                    ]
                );
                $services = array();
                foreach ($row->services as $sv) {
                    $services[] = array(
                        'id' => $sv->id,
                        'name' => $sv->name
                    );
                }
                $tmp['services'] = $services;

                $bookings[] = $tmp;
            }
        }

        $this->set(compact('bookings'));
        $this->set('_serialize', 'bookings');
    }

    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $query = $this->Bookings->find()
                    ->contain(['Customers', 'Services'])
                    ->where([
                            'Customers.shops_id' => $permission->shops_id,
                            'Bookings.id' => $id
                        ]);

        if ($query->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $bookingResult = $query->first();
        $booking = array(
            'id' => $bookingResult->id,
            'date' => $bookingResult->date,
            'start_time' => $bookingResult->start_time,
            'end_time' => $bookingResult->end_time,
            'status' => $bookingResult->status,
            'note' => $bookingResult->note,
            'customer' => [
                'id' => $bookingResult->customer->id,
                'first_name' => $bookingResult->customer->first_name,
                'last_name' => $bookingResult->customer->last_name
            ]
        );

        $services = array();
        foreach ($bookingResult->services as $row) {
            $tmp = array(
                'id' => $row->id,
                'name' => $row->name
            );
            $services[] = $tmp;
        }
        $booking['services'] = $services;

        $this->set('booking', $booking);
        $this->set('_serialize', 'booking');
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

        $servicesTable = TableRegistry::get('Services');
        $query = $servicesTable->find()
            ->contain(['Categories'])
            ->where(['Categories.shops_id' => $permission->shops_id])
            ->andWhere(function ($exp, $q) use ($serviceData) {
                return $exp->in('Services.id', $serviceData);
            });
        if ($query->count() < sizeof($serviceData)) {
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
        if ($bookingDate <= Time::now() || $bookingStart >= $bookingEnd) {
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
        foreach ($serviceData as $serviceId) {
            $tmp = array(
                'bookings_id' => $bookingId,
                'services_id' => $serviceId,
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

        $booking = $this->Bookings->get($id);
        if (is_null($booking)) {
            $result = $this->_getResult('failed', 404, $this->msg['not_found']);
            $this->_handleResponse($result);
            return;
        }

        $servicesData = $this->request->data('services');
        if (empty($servicesData)) {
            $result = $this->_getResult('error', 400, $this->msg['booking_service_not_empty']);
            $this->_handleResponse($result);
            return;
        }
        
        $booking = $this->Bookings->patchEntity($booking, $this->request->data);

        $services = array();
        foreach ($servicesData as $serviceId) {
            $service = [
                'bookings_id' => $booking->id,
                'services_id' => $serviceId
            ];
            $services[] = $service;
        }

        if ($this->Bookings->save($booking)) {
            $result = $this->_getResult('success', 200, $this->msg['edit_success']);
            $bookingsHasServicesTable = TableRegistry::get('BookingsHasServices');
            $bookingsHasServicesTable->addMultiple($services);
        } else {
            $result = $this->_getResult('failed', 400, $this->msg['edit_failed'], $booking->errors());
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
