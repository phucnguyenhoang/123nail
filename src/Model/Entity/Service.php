<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property int $categories_id
 * @property string $name
 * @property float $price
 * @property float $shop_fee
 * @property bool $active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Category $category
 */
class Service extends Entity
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
        'categories_id',
        'created',
        'modified'
    ];
}
