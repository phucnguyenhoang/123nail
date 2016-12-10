<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BookingsHasServices Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Bookings
 * @property \Cake\ORM\Association\BelongsTo $Services
 *
 * @method \App\Model\Entity\BookingsHasService get($primaryKey, $options = [])
 * @method \App\Model\Entity\BookingsHasService newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BookingsHasService[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BookingsHasService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BookingsHasService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BookingsHasService[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BookingsHasService findOrCreate($search, callable $callback = null)
 */
class BookingsHasServicesTable extends Table
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

        $this->table('bookings_has_services');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Bookings', [
            'foreignKey' => 'bookings_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'services_id',
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
            ->allowEmpty('note');

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
        $rules->add($rules->existsIn(['bookings_id'], 'Bookings'));
        $rules->add($rules->existsIn(['services_id'], 'Services'));

        return $rules;
    }

    public function addMultiple($data) {
        $bookingServices = $this->newEntities($data);
        foreach ($bookingServices as $bookingService) {
            $this->save($bookingService);
        }
    }
}
