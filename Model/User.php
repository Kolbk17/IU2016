<?php
App::uses('AppModel', 'Model', 'UserStarEdge');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    var $name = 'User';
    public $belongsTo = array(
        'Star' => array(
            'className'     => 'star',
            'conditions'    => '',
            'order'         => '',
            'foreignKey'    => 'star_id'
        )
    );
    public $hasOne = array(
        'UserStarEdge' => array(
            'className' => 'UserStarEdge',
            'conditions'   => '',
            'dependent'    => 'true',
            'foreignKey'  => ''
        )
    );
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'star_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A star_id is required'
            )
        )
    );
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
}


