<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * --------------- ORM ---------------
 * # Table class (The database table, collection of entities)
 * - Associations (các mối quan hệ giữa các bảng)
 * - Behaviors (Helper classes for the model layer)
 * - Validation (Validating data)
 * - Actually communicating with the actual database backend
 * - Các truy vấn, cách lưu ntn sẽ viết ở đây
 *
 * # Entities (A database row, collection of data that makes up a single record)
 * - Tập trung vào việc data
 * - Chẳng hạn như form submit để thêm gì đó, 1 instance của entity
 * - Interact with the single object --> means the entity
 */

/**
 * Users Model
 *
 * @property \App\Model\Table\BookmarksTable|\Cake\ORM\Association\HasMany $Bookmarks
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Bookmarks', [
            'foreignKey' => 'user_id',

            // When 'dependent' is set to true, and an entity is deleted, the data of the join table will be deleted.
            'dependent' => true,
        ]);
        $this->hasOne('LastBookmarks', [
            'className' => 'Bookmarks',
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    // Validator class contains our validation rules ($validator: instance)
    public function validationDefault(Validator $validator)
    {
        # Store those stateless validation rules
        # Apply khi mình create hoặc modify an entity (new entity rồi patch entity -> validate liền)
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create'); // Allow to be empty when we create the record

        $validator
            ->email('email') // Ensure that is a valid email
            ->requirePresence('email', 'create') // Require that email at least has some value set
            ->notEmptyString('email'); // Ensure that value is NOT either a null or an empty string ('')

        // Scalar: accept integers, floats, strings and booleans, but not accept arrays, objects, resources and nulls.
        $validator
            ->scalar('password')
            ->add('password', 'nganNhat', ['rule' => ['minLength', 6]]) // nganNhat sẽ là tên lỗi hiện trong mảng errors
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('firstname')
            ->minLength('firstname', 3)
            ->maxLength('firstname', 255)
            ->requirePresence('firstname', 'create')
            ->notEmptyString('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->requirePresence('lastname', 'create')
            ->notEmptyString('lastname');

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
        # Apply khi mình save an entity
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
