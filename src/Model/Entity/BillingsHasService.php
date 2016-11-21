<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BillingsHasService Entity
 *
 * @property int $id
 * @property int $billings_id
 * @property int $services_id
 * @property int $employees_id
 * @property float $price
 * @property float $shop_fee
 * @property float $discount
 * @property float $tips
 *
 * @property \App\Model\Entity\Billing $billing
 * @property \App\Model\Entity\Service $service
 * @property \App\Model\Entity\Employee $employee
 */
class BillingsHasService extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
