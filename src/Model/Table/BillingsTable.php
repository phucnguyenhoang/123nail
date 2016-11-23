<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Billings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\Billing get($primaryKey, $options = [])
 * @method \App\Model\Entity\Billing newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Billing[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Billing|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Billing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Billing[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Billing findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BillingsTable extends Table
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

        $this->table('billings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customers_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('BillingsHasServices', [
            'className' => 'BillingsHasServices',
            'foreignKey' => 'billings_id',
            'propertyName' => 'services',
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->integer('payment_type')
            ->allowEmpty('payment_type');

        $validator
            ->numeric('receive')
            ->allowEmpty('receive');

        $validator
            ->numeric('returns')
            ->allowEmpty('returns');

        $validator
            ->allowEmpty('note');

        $validator
            ->boolean('done')
            ->allowEmpty('done');

        $validator
            ->dateTime('billing_date')
            ->allowEmpty('billing_date');

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
        $rules->add($rules->existsIn(['customers_id'], 'Customers'));

        return $rules;
    }
}
