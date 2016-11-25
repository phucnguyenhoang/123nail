<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

class ReportsController extends ApiController
{
    public function business()
    {     
        // echeck session and permission
        $permission = $this->checkPermission(true);
        if (is_array($permission)) {
            $this->_handleResponse($permission);
            return;
        }

        
    }
}
