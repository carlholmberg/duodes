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
    
    function fromGoogle($data) {
        if ($data['from'] != 'google') {
            return false;
        }
        $account = \R::findOne('account', ' email = :email', 
            array(':email' => $data['email'])
        );
        if (!$account && in_array($data['email'], $this->app->get('autoaccount'))) {
            $this->app->reroute('/account/create');
        }
        
        
            $this->info = array('name' => $data['fname'].' '.$data['lname'], 'email' => $data['email'], 'level' => 3);
        if (!$account) {
            return false;
        }
    }
    
    function fromLocal($data) {
    $acc = array('name' => 'Noname', 'email' => $data['email'], 'level' => 3);
    }
    
    function info() {
        return $this->info;
    }
}