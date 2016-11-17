<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Employee Entity
 *
 * @property int $id
 * @property int $shops_id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $telephone
 * @property int $avatar
 * @property int $salary_type
 * @property float $percent
 * @property float $hourly_rate
 * @property \Cake\I18n\Time $working_date
 * @property \Cake\I18n\Time $leaving_date
 * @property bool $active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Shop $shop
 */
class Employee extends Entity
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

    protected $_hidden = [
        'shops_id',
        'created',
        'modified'
    ];
}
