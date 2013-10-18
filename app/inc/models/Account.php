<?php

/**
 * Account model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Account extends \models\DBModel {
    
    /* Model: Account
        id [auto]
        user [Model.User]
        level [0-3]
        email [str]
        salt [salt()]
        password [hash(str, salt)]
        type [inactive, google, local]
    */
    
    function __construct($email) {
        $this->data = \R::findOne('account', ' email = :email', 
            array(':email' => $email)
        );
        $this->exists = false;
        if ($this->data) {
            $this->exists = true;
            $this->active = ($this->data->type != 'inactive');
        }
    }
    
    
    function createUser() {
        
    }
    
    function connectUser($uid) {
        $this->data->user = \R::load('user', $uid);
        $this->save();
    }
    
    function info() {
        if ($this->data) {
            return array('name' => $this->data->name,
                         'email' => $this->data->email,
                         'level' => $this->data->level);
        }
        return false;
    }
}