<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class BillingsController extends ApiController
{
    public function index() 
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $billingsTable = TableRegistry::get('Billings');
        $bills = $billingsTable->find()
                ->contain(['Customers', 'BillingsHasServices'])
                ->where(['Billings.done' => 0, 'Customers.shops_id' => $permission->shops_id])
                ->order(['Billings.created' => 'ASC']);

        if ($bills->count() <= 0) {
            $this->_handleResponse([]);
            return;
        }
        
        $billInfo = array();
        foreach ($bills as $bill) {
            $currBill = array();
            $currBill['id'] = $bill->id;
            $currBill['customer'] = $bill->customer->first_name.' '.$bill->customer->last_name;

            $total = 0;
            $shopFee = 0;
            $discount = 0;
            $tips = 0;
            foreach ($bill->services as $service) {
                $total += !is_null($service->price) ? $service->price : 0;
                $shopFee += !is_null($service->shop_fee) ? $service->shop_fee : 0;
                $discount += !is_null($service->discount) ? $service->discount : 0;
                $tips += !is_null($service->tips) ? $service->tips : 0;
            }
            $currBill['services'] = sizeof($bill->services);
            $currBill['total'] = $total;
            $currBill['shop_fee'] = $shopFee;
            $currBill['discount'] = $discount;
            $currBill['tips'] = $tips;

            $billInfo[] = $currBill;
        }

        $this->_handleResponse($billInfo);
    }

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
        $services = $data['services'];

        // verify service data
        $verify = $this->_verifyServiceData($services, $permission->shops_id);
        if ($verify['error']) {
            $this->_handleResponse($verify['data']);
            return;
        }
        
        // verify customer data
        $customersTable = TableRegistry::get('Customers');
        $customerId = null;
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
        $billId = $bill->id;

        // make bill data
        $billData = $this->_makeBillData($billId, $services, $verify['data']);
        
        // save bill data
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $billingsHasServicesTable->addMultiple($billData);

        $result = $this->_getResult('success', 200, $this->msg['create_success'], ['id' => $billId]);
        $this->_handleResponse($result);
        return;
    }

    public function addService($id = null) 
    {
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $billingsTable = TableRegistry::get('Billings');
        $bill = $billingsTable->find()->contain('Customers')->where(['Billings.id' => $id, 'Customers.shops_id' => $permission->shops_id]);

        // verify existing bill
        if ($bill->count() <= 0) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_not_found']);
            $this->_handleResponse($result);
            return;
        }

        // verify is done bill
        $bill = $bill->first();
        if ($bill->done) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_not_accept_service']);
            $this->_handleResponse($result);
            return;
        }

        $services = $this->request->data['services'];

        // verify service data
        $verify = $this->_verifyServiceData($services, $permission->shops_id);
        if ($verify['error']) {
            $this->_handleResponse($verify['data']);
            return;
        }

        // make bill data
        $billData = $this->_makeBillData($bill->id, $services, $verify['data']);
        
        // save bill data
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $billingsHasServicesTable->addMultiple($billData);

        $result = $this->_getResult('success', 200, $this->msg['create_success']);
        $this->_handleResponse($result);
    }

    public function removeService($id = null) 
    {
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $billingsTable = TableRegistry::get('Billings');
        $bill = $billingsTable->find()
                ->contain(['Customers', 'BillingsHasServices'])
                ->where(['Billings.id' => $id, 'Customers.shops_id' => $permission->shops_id]);

        // verify existing bill
        if ($bill->count() <= 0) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_not_found']);
            $this->_handleResponse($result);
            return;
        }

        // verify is done bill
        $bill = $bill->first();
        if ($bill->done) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_not_accept_service']);
            $this->_handleResponse($result);
            return;
        }

        // verify exist service id
        $billServices = array();
        foreach ($bill->services as $service) {
            $billServices[] = $service->id;
        }
        
        $billServicesId = $this->request->data;
        foreach ($billServicesId as $billServiceId) {
            if (!in_array($billServiceId, $billServices)) {
                $result = $this->_getResult('failed', 400, $this->msg['bill_service_not_found']);
                $this->_handleResponse($result);
                return;
            }            
        }

        // delete service from bill
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        foreach ($billServicesId as $billServiceId) {
            $service = $billingsHasServicesTable->get($billServiceId);

            $billingsHasServicesTable->delete($service);
        }

        $result = $this->_getResult('success', 200, $this->msg['delete_success']);
        $this->_handleResponse($result);
    }

    public function view($id = null) 
    {
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $billingsTable = TableRegistry::get('Billings');
        $bill = $billingsTable->find()->contain('Customers')->where(['Billings.id' => $id, 'Customers.shops_id' => $permission->shops_id]);

        // verify existing bill
        if ($bill->count() <= 0) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        // get bill detail
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $billServices = $billingsHasServicesTable->find()
                        ->select([
                            'id', 'price', 'shop_fee', 'discount', 'tips',
                            'Services.name',
                            'Employees.first_name',
                            'Employees.last_name'
                        ])
                        ->contain(['Services', 'Employees'])
                        ->where(['BillingsHasServices.billings_id' => $id]);
        
        $serviceInfo = array();
        $total = 0;
        $shopFee = 0;
        $discount = 0;
        $tips = 0;
        foreach ($billServices as $row) {
            $tmp = array();
            $tmp['id'] = $row->id;
            $tmp['service_name'] = $row->service->name;
            $tmp['employee_name'] = $row->employee->first_name.' '.$row->employee->last_name;
            $tmp['price'] = $row->price;
            $tmp['shop_fee'] = $row->shop_fee;
            $tmp['discount'] = $row->discount;
            $tmp['tips'] = $row->tips;

            $total += !is_null($row->price) ? $row->price : 0;
            $shopFee += !is_null($row->shop_fee) ? $row->shop_fee : 0;
            $discount += !is_null($row->discount) ? $row->discount : 0;
            $tips += !is_null($row->tips) ? $row->tips : 0;

            $serviceInfo[] = $tmp;
        }

        $billInfo = $bill->first()->toArray();
        unset($billInfo['customers_id']);
        $billInfo['customer'] = $billInfo['customer']['first_name'].' '.$billInfo['customer']['last_name'];
        $billInfo['total'] = $total;
        $billInfo['shop_fee'] = $shopFee;
        $billInfo['discount'] = $discount;
        $billInfo['tips'] = $tips;
        $billInfo['services'] = $serviceInfo;

        $this->_handleResponse($billInfo);
    }

    public function discount($id = null) 
    {
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $billService = $billingsHasServicesTable->get($id, ['contain' => ['Billings', 'Billings.Customers']]);

        if ($billService->billing->customer->shops_id != $permission->shops_id) {
            $result = $this->_getResult('failed', 400, $this->msg['bill_service_not_found']);
            $this->_handleResponse($result);
            return;
        }

        $billService->discount = $this->request->data('discount');
        $billingsHasServicesTable->save($billService);

        $result = $this->_getResult('success', 200, $this->msg['edit_success']);
        $this->_handleResponse($result);
    }

    /************** Private function ****************/

    private function _verifyServiceData($services, $shopId) 
    {
        $result = array(
            'error' => false,
            'data' => null
        );
        if (empty($services)) {
            $result['error'] = true;
            $result['data'] = $this->_getResult('error', 400, $this->msg['missing_parameter']);
            return $result;
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
                $service = $servicesTable->find()->contain('Categories')->where(['Services.id' => $row['services_id'], 'Categories.shops_id' => $shopId]);
                if ($service->count() <= 0) {
                    $result['error'] = true;
                    $result['data'] = $this->_getResult('error', 400, $this->msg['service_not_found']);
                    return $result;
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
                $employee = $employeesTable->find()->where(['id' => $row['employees_id'], 'shops_id' => $shopId]);
                if ($employee->count() <= 0) {
                    $result['error'] = true;
                    $result['data'] = $this->_getResult('error', 400, $this->msg['employee_not_found']);
                    return $result;
                }
            }
        }

        $result['data'] = $serviceInfo;
        return $result;
    }

    private function _makeBillData($billId, $services, $data) 
    {
        $billData = array();
        foreach ($services as $service) {
            $row = array(
                'billings_id' => $billId,
                'services_id' => $service['services_id'],
                'employees_id' => $service['employees_id'],
                'price' => $data[$service['services_id']]['price'],
                'shop_fee' => $data[$service['services_id']]['shop_fee']
            );

            $billData[] = $row;
        }

        return $billData;
    }
}
