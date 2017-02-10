<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;

class ReportsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->layout('123nail');
    }

    public function salary()
    {
        $shopId = $this->request->query('shop');
        $employeeId = $this->request->query('employee');
        $fromDate = $this->request->query('fromDate');
        $toDate = $this->request->query('toDate');
        
        $shops = TableRegistry::get('Shops')->find()
                                            ->select(['id', 'account'])
                                            ->where(['active' => 1]);

        $employees = TableRegistry::get('Employees')->find()
                                                    ->select(['id', 'first_name', 'last_name'])
                                                    ->where(['active' => 1]);
        if (!empty($shopId)) {
            $employees = $employees->where(['shops_id' => $shopId]);
        }

        $time = Time::now();
        if (empty($fromDate)) {
            $fromDate = new Time($time->year.'-'.$time->month.'-'.'01');
        } else {
            $fromDate = new Time($fromDate);
        }
        if (empty($toDate)) {
            $toDate = Time::now();
        } else {
            $toDate = new Time($toDate);
        }

        $conditions = array(
            'shopId' => $shopId,
            'employeeId' => $employeeId,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        );

        $query = TableRegistry::get('BillingsHasServices')->find();
        $query->contain(['Employees', 'Billings']);
        $query->select([
            'employees_id',
            'full_name' => $query->func()->concat([
                'Employees.first_name' => 'identifier',
                ' ',
                'Employees.last_name' => 'identifier'
            ]),
            'tPrice' => $query->func()->sum('price'),
            'tShopFee' => $query->func()->sum('shop_fee'),
            'tTips' => $query->func()->sum('tips')
        ])
        ->group('employees_id')
        ->where([
            'Billings.billing_date >=' => $fromDate,
            'Billings.billing_date <' => $toDate->addDays(1)
        ]);
        if (!empty($shopId)) {
            $query->where(['Employees.shops_id' => $shopId]);
        }
        if (!empty($employeeId)) {
            $query->where(['BillingsHasServices.employees_id' => $employeeId]);
        }

        $data = $query->all();

        $this->set(
            ['shops', 'employees', 'conditions', 'data'],
            [$shops, $employees, $conditions, $data]
        );
    }

    public function employeeList($shopId) {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }

        $employees = TableRegistry::get('Employees')->find()
                                                    ->select(['id', 'first_name', 'last_name'])
                                                    ->where(['active' => 1]);
        if (!empty($shopId)) {
            $employees->where(['shops_id' => $shopId]);
        }

        $result = array();
        foreach ($employees as $employee) {
            $result[$employee->id] = $employee->first_name.' '.$employee->last_name;
        }

        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
