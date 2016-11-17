<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserSession Entity
 *
 * @property int $id
 * @property int $users_id
 * @property string $udid
 * @property \Cake\I18n\Time $login_date
 * @property \Cake\I18n\Time $logout_date
 *
 * @property \App\Model\Entity\User $user
 */
class UserSession extends Entity
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
