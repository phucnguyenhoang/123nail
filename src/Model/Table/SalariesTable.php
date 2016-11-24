<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Salaries Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\Salary get($primaryKey, $options = [])
 * @method \App\Model\Entity\Salary newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Salary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Salary|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Salary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Salary[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Salary findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SalariesTable extends Table
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

        $this->table('salaries');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Employees', [
            'foreignKey' => 'employees_id',
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
            ->dateTime('from_date')
            ->allowEmpty('from_date');

        $validator
            ->dateTime('to_date')
            ->allowEmpty('to_date');

        $validator
            ->numeric('price')
            ->allowEmpty('price');

        $validator
            ->numeric('shop_fee')
            ->allowEmpty('shop_fee');

        $validator
            ->numeric('discount')
            ->allowEmpty('discount');

        $validator
            ->numeric('tips')
            ->allowEmpty('tips');

        $validator
            ->integer('salary_type')
            ->allowEmpty('salary_type');

        $validator
            ->numeric('percent')
            ->allowEmpty('percent');

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
        $rules->add($rules->existsIn(['employees_id'], 'Employees'));

        return $rules;
    }
}
