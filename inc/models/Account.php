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
    
    function __construct($data) {
        if ($data['from'] == 'google') {
            $this->info = array('name' => $data['fname'].' '.$data['lname'], 'email' => $data['email'], 'level' => 3);
            return true;
        }
        $account = \R::find('account', ' email = :email LIMIT 1', 
            array(':email' => $data['email'])
        );
        if (!$account) {
            return false;
        }
    }
    
    function info() {
        return $this->info;
    }
}