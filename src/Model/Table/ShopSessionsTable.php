<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * ShopSessions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Shops
 *
 * @method \App\Model\Entity\ShopSession get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShopSession newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ShopSession[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShopSession|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShopSession patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShopSession[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShopSession findOrCreate($search, callable $callback = null)
 */
class ShopSessionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('shop_sessions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Shops', [
            'foreignKey' => 'shops_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('udid', 'create')
            ->notEmpty('udid');

        $validator
            ->dateTime('login_date')
            ->requirePresence('login_date', 'create')
            ->notEmpty('login_date');

        $validator
            ->dateTime('logout_date')
            ->allowEmpty('logout_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['shops_id'], 'Shops'));

        return $rules;
    }

    public function createSession($shopId, $udid)
    {
        $this->query()
            ->update()
            ->set(['logout_date' => Time::now()])
            ->where(['udid' => $udid])
            ->execute();

        $this->query()
            ->insert(['shops_id', 'udid'])
            ->values([
                'shops_id' => $shopId,
                'udid' => $udid])
            ->execute();
    }
}
