<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class SalariesController extends ApiController
{    
    public function view($id = null)
    {
        // echeck session and permission
        $permission = $this->checkPermission();
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        $query = $this->request->query;
        
        if (empty($query['from'])) {
            $d = date('Y-m').'-01';
            $fromDate = new Time($d);
        } else {
            $fromDate = new Time($query['from']);
        }
        if (empty($query['to'])) {
            $toDate = Time::now();
        } else {
            $toDate = new Time($query['to']);
        }
        if ($fromDate > $toDate) {
            $result = $this->_getResult('error', 400, $this->msg['salary_date_error']);
            $this->_handleResponse($result);
            return;
        }

        // verify employe id
        $employe = TableRegistry::get('Employees')->find()
                    ->where(['id' => $id, 'shops_id' => $permission->shops_id]);
        if ($employe->count() <= 0) {
            $result = $this->_getResult('failed', 404, $this->msg['employee_not_found']);
            $this->_handleResponse($result);
            return;
        }
        
        $billingsHasServicesTable = TableRegistry::get('BillingsHasServices');
        $services = $billingsHasServicesTable->find('all')
                    ->contain(['Billings', 'Services'])
                    ->where([
                        'BillingsHasServices.employees_id' => $id,
                        'Billings.done' => 1,
                        'Billings.billing_date >=' => $fromDate,
                        'Billings.billing_date <' => $toDate->addDays(1)
                    ])
                    ->order(['Billings.billing_date' => 'DESC']);

        if ($services->count() <= 0) {
            $this->_handleResponse([]);
            return;
        }

        $lastSalaryDate = TableRegistry::get('Salaries')->find()
                            ->select(['from_date', 'to_date'])
                            ->where(['employees_id' => $id])
                            ->order(['to_date' => 'DESC'])
                            ->first();
                
        $salaries = array();
        $price = 0;
        $shopFee = 0;
        $discount = 0;
        $tips = 0;
        foreach ($services as $service) {
            $tmp = array();
            $tmp['date'] = $service->billing->billing_date;
            $tmp['name'] = $service->service->name;
            $tmp['price'] = $service->price;
            $tmp['shop_fee'] = $service->shop_fee;
            $tmp['discount'] = $service->discount;
            $tmp['tips'] = $service->tips;
            $tmp['received'] = false;
            if (!is_null($lastSalaryDate) && $tmp['date'] >= $lastSalaryDate->from_date && $tmp['date'] < $lastSalaryDate->to_date) {
                $tmp['received'] = true;
                $salaries[] = $tmp;
                continue;
            }
            $price += !is_null($service->price) ? $service->price : 0;
            $shopFee += !is_null($service->shop_fee) ? $service->shop_fee : 0;
            $discount += !is_null($service->discount) ? $service->discount : 0;
            $tips += !is_null($service->tips) ? $service->tips : 0;

            $salaries[] = $tmp;
        }

        $result = array(
            'from' => $fromDate,
            'to' => $toDate,
            'price' => $price,
            'shop_fee' => $shopFee,
            'discount' => $discount,
            'tips' => $tips,
            'services' => $salaries
        );
        
        $this->_handleResponse($result);
        return;
    }
}
