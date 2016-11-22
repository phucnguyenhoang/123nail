<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class BillingsController extends ApiController
{
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

        // verify service data
        $services = $data['services'];
        if (empty($services)) {
            $result = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            $this->_handleResponse($result);
            return;
        }
        $arrServiceId = array();
        $arrEmployeeId = array();
        $serviceInfo = array();
        $servicesTable = TableRegistry::get('Services');
        $employeesTable = TableRegistry::get('Employees');
        foreach ($services as $row) {
            // verify service id
            if (!in_array($row['services_id'], $arrServiceId)) {
                $arrServiceId[] = $row['services_id'];
                $service = $servicesTable->find()->contain('Categories')->where(['Services.id' => $row['services_id'], 'Categories.shops_id' => $permission->shops_id]);
                if ($service->count() <= 0) {
                    $result = $this->_getResult('failed', 400, $this->msg['service_not_found']);
                    $this->_handleResponse($result);
                    return;
                } else {
                    $service = $service->first();
                    $serviceInfo[$service->id] = array(
                        'price' => $service->price,
                        'shop_fee' => $service->shop_fee
                    );
                }
            }
            // verify employee id
            if (!in_array($row['employees_id'], $arrEmployeeId)) {
                $arrEmployeeId[] = $row['employees_id'];
                $employee = $employeesTable->find()->where(['id' => $row['employees_id'], 'shops_id' => $permission->shops_id]);
                if ($employee->count() <= 0) {
                    $result = $this->_getResult('failed', 400, $this->msg['employee_not_found']);
                    $this->_handleResponse($result);
                    return;
                }
            }
        }
        
        // verify customer data
        $customersTable = TableRegistry::get('Customers');
        if (is_array($data['customers_id'])) {
            $customerData = $data['customers_id'];
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
            $customerId = $data['customers_id'];
            $customer = $customersTable->find()->where(['id' => $customerId, 'shops_id' => $permission->shops_id]);
            if ($customer->count() <= 0) {
                $result = $this->_getResult('failed', 400, $this->msg['customer_not_found']);
                $this->_handleResponse($result);
                return;
            }
        }

        $billingsTable = TableRegistry::get("Billings");
        $bill = $billingsTable->newEntity();
        $bill->customers_id = $customerId;
        $bill = $billingsTable->save($bill);
        $billingId = $bill->id;

        // make bill data
        $billData = array();
        foreach ($services as $service) {
            $row = array(
                'billings_id' => $billingId,
                'services_id' => $service['services_id'],
                'employees_id' => $service['employees_id'],
                'price' => $serviceInfo[$service['services_id']]['price'],
                'shop_fee' => $serviceInfo[$service['services_id']]['shop_fee']
            );

            $billData[] = $row;
        }
        
        // save bill data
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $billServices = $billingsHasServicesTable->newEntities($billData);
        foreach ($billServices as $billService) {
            $billingsHasServicesTable->save($billService);
        }

        $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $billingId]);
        $this->_handleResponse($result);
        return;
    }
}
