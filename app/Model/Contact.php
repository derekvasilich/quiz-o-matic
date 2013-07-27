<?
App::uses('AppModel', 'Model');

class Contact extends AppModel {
    const TYPE_PRIMARY     = 'P';
    const TYPE_APPLICATION = 'A';   
    const TYPE_FORWARDING  = 'F';
    const TYPE_BACKUP      = 'B';
    const TYPE_EMERGENCY   = 'E';
    const TYPE_OFFICE 	   = 'O';
    	
    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }
/*
    
    public function beforeValidate($options = array()) {
        if (isset($this->data['Contact']['type'])) {
            switch ($this->data['Contact']['type']) {
                case self::TYPE_APPLICATION: // these are not required
                case self::TYPE_EMERGENCY:
                case self::TYPE_FORWARDING: 
                    unset($this->validate);
                    break;
                case self::TYPE_PRIMARY:
                case self::TYPE_OFFICE:
                default: 
            }
        }
        return parent::beforeValidate($options);
    }
*/
     public $validate = array( 
        'name'      => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'relation'  => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'address_1' => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'city'      => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'province'  => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'country'   => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'postal'    => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'phone'     => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
        'cell'      => array('rule'=>'notEmpty', 'required' => 'pseudo', 'allowEmpty' => true),
    );   
 
}

?>