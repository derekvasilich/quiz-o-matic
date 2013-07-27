<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthActionsMap {
        
    public static $defaults = array(
        'create' => array('add'),
        'read' => array('index', 'view', 'search', 'display'),
        'update' => array('edit'),
        'delete' => array('delete')
    );
    
    public static $map = array(
        'users' => array(
            'create' => array('add', 'signup'),
            'read' => array('index', 'view', 'incomplete'),
            'update' => array('edit', 'impersonate'),
            'delete' => array('delete'),
        ),
    );
    
    public static function CRUD($controller = null) {
        return ($controller && isset(self::$map[$controller])) ? self::$map[$controller] : self::$defaults;
    }
    
}

?>
