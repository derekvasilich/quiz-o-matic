<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
      
    public $belongsTo = array(
    );
    
    public $hasMany = array(
        'Contact' => array(
                'className' => 'Contact',
                'foreignKey' => 'user_id'
        )
    );
	    
//    public $actsAs = array('Acl' => array('type' => 'requester'));
    
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['name'] = sprintf('CONCAT(%s.first_name, " ", %s.last_name)', $this->alias, $this->alias);
    }
    
//    public function parentNode() {
//        if (!$this->id && empty($this->data)) {
//            return null;
//        }
//        if (isset($this->data[$this->alias]['group_id'])) {
//            $groupId = $this->data[$this->alias]['group_id'];
//        } else {
//            $groupId = $this->field('group_id');
//        }
//        if (!$groupId) {
//            return null;
//        } else {
//            return array('Group' => array('id' => $groupId));
//        }
//    }

    public function passwordsMatch ($data, $controlField) {
        if (!isset($this->data[$this->alias][$controlField])) {
            trigger_error('Password control field not set.');
            return false;
        }

        $controlPassword = $this->data[$this->alias][$controlField];
        if (current($data) === $controlPassword) 
            return true;

        $this->invalidate($controlField, 'Must match password above.');
        return false;
    }
    
    public function beforeSave($options = array()) {   
        if (!isset($this->data[$this->alias]['password']))
            return parent::beforeSave($options);
        $pwd = $this->data[$this->alias]['password'];
        if ($pwd === '')
            unset($this->data[$this->alias]['password']);
        else 
            $this->data[$this->alias]['password'] = AuthComponent::password($pwd);
        return parent::beforeSave($options);
    }
        
    public $validate = array(
        
        'username' => array(
            'required' => array('rule' => 'notEmpty'),
            'matches'  => array(
                'rule'    => array('isUnique'),
                'on'      => 'create',
                'message' => 'This username has already been taken.'
            )
        ),
        'first_name' => array('rule' => 'notEmpty'),
        'last_name'  => array('rule' => 'notEmpty'),
        'email'  => array('rule' => 'email', 'message' => 'Please enter a vaild email.'),
        'password' => array(
            'required' => array(
                'rule' => array('between', 6, 20),
                'message' => 'Length must be 6 to 20 characters.'
            ),
            'matches' => array(
                'rule' => array('passwordsMatch', 'confirm_password'),
                'message' => 'Passwords must match.'
            )
        ),
        'confirm_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Length must be 6 to 20 characters.'
            )
        )
    ); 
}

?>
