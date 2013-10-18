<?php

/**
 * Account controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Account extends \controllers\ViewController {
    
    static function salt() {
        return substr(str_shuffle(MD5(microtime())), 0, 5);
    }
    
    static function hash($pw, $salt) {
        $salt = md5($salt);
        $pw = md5($pw);
        return sha1(md5($salt . $pw) . $salt);
    }
    
    static function verify($pw, $hash, $salt) {
        return self::hash($pw, $salt) == $hash;
    }
    
    
    function fromGoogle($data) {
        $account = new \models\Account($data['email']);
        if ($account->exists) {
            if ($account->active) {
                return $account->info();
            } else {
                $this->app->set('SESSION.tmpdata', $data);
                $this->app->set('SESSION.accountfrom', 'google');
                $this->app->reroute('/account/create');
            }
        }
        return false;
    }
    
    
    function fromLocal($data) {
        $account = new \models\Account($data['email']);
        if ($account->exists) {
            if (self::verify($data['password'], $account->data->password, $account->data->salt)) {
                return $account->info();
            }
            return false;
        }
        return false;
    }
    
    function get($app, $params) {
        if ($params['id'] == 'create') {
            if ($this->app->get('POST.email')) {
                $account = new \models\Account($this->app->get('POST.email'));
                $data = array('name' => $this->app->get('POST.name'),
                              'password' => $this->app->get('POST.password'),
                              'type' => $this->app->get('SESSION.accountfrom'));
                $account->update($data);
                if ($this->app->get('POST.createuser')) {
                    $account->createUser();
                }
                $this->app->reroute('/');
            }
            $data = $this->app->get('SESSION.tmpdata');
            if (!is_array($data)) $this->app->error(404);
            $this->slots['pagetitle'] = 'Konto';
            $this->slots = array_merge($this->slots, $this->app->get('SESSION.tmpdata'));
            $this->setPage('account-create');
        } else if ($params['id'] == 'apply') {
            $this->slots['pagetitle'] = 'Ansök';
            $this->slots = array_merge($this->slots, $this->app->get('SESSION.tmpdata'));
            $this->setPage('account-apply');
        }
    }
    
    function post($app, $params) {
        if ($params['id'] == 'create' && $this->app->get('POST.email')) {
            $account = new \models\Account($this->app->get('POST.email'));
            $salt = self::salt();
            $data = array('name' => $this->app->get('POST.name'),
                          'password' => self::hash($this->app->get('POST.password'), $salt),
                          'salt' => $salt,
                          'type' => $this->app->get('SESSION.accountfrom'));
            $account->update($data);
            if ($this->app->get('POST.createuser')) {
                $account->createUser();
            }
            $account->save();
        }
        $this->app->reroute('/');
    }
    
    function put() {}
    function delete() {}
}