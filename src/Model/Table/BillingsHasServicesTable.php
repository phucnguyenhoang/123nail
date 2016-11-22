<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BillingsHasServices Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Billings
 * @property \Cake\ORM\Association\BelongsTo $Services
 * @property \Cake\ORM\Association\BelongsTo $Employees
 *
 * @method \App\Model\Entity\BillingsHasService get($primaryKey, $options = [])
 * @method \App\Model\Entity\BillingsHasService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BillingsHasService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BillingsHasService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BillingsHasService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BillingsHasService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BillingsHasService findOrCreate($search, callable $callback = null)
 */
class BillingsHasServicesTable extends Table
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

        $this->table('billings_has_services');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Billings', [
            'foreignKey' => 'billings_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'services_id',
            'joinType' => 'INNER'
        ]);
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
        $rules->add($rules->existsIn(['billings_id'], 'Billings'));
        $rules->add($rules->existsIn(['services_id'], 'Services'));
        $rules->add($rules->existsIn(['employees_id'], 'Employees'));

        return $rules;
    }

    public function addMultiple($data) {
        $billServices = $this->newEntities($data);
        foreach ($billServices as $billService) {
            $this->save($billService);
        }
    }
}
