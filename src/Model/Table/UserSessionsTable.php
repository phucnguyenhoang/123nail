<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * UserSessions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserSession get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserSession newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserSession[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserSession|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserSession patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserSession[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserSession findOrCreate($search, callable $callback = null)
 */
class UserSessionsTable extends Table
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

        $this->table('user_sessions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'users_id',
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
            ->allowEmpty('udid');

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
        $rules->add($rules->existsIn(['users_id'], 'Users'));

        return $rules;
    }

    public function createSession($userId, $udid)
    {
        $this->query()
            ->update()
            ->set(['logout_date' => Time::now()])
            ->where(['users_id' => $userId, 'udid' => $udid])
            ->execute();

        $this->query()
            ->insert(['users_id', 'udid'])
            ->values([
                'users_id' => $userId,
                'udid' => $udid])
            ->execute();
    }
}
