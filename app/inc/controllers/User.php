<?php

/**
 * User controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class User extends \controllers\ViewController {
    
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
        
        $user = new \models\User('email', $data['email']);
        if ($user->exists) {
            if ($user->active) {
                return $user->info();
            } else {
                $this->app->set('SESSION.tmpdata', $data);
                $this->app->reroute('/user/create');
            }
        } else {
            if (strpos($data['email'], 'kunskapsgymnasiet.se')) {
                // För KGYM
                $this->app->set('SESSION.tmpdata', $data);
                $this->app->reroute('/user/create');
            }
        }
        
        return false;
    }
    
    
    function fromLocal($data) {
        $user = new \models\User('email', $data['email']);
        if ($user->exists) {
            if (self::verify($data['password'], $user->data->password, $user->data->salt)) {
                // TMP
                if ($user->data->email == 'carl.holmberg@kunskapsgymnasiet.se') {
                    $user->update(array('level' => 4));
                    $user->save();
                }
                // --TMP
                return $user->info();
            }
            return false;
        }
        return false;
    }
    
    
    function reformatUID($uid) {
        $uid = implode('', explode('-', $uid));
        if (strlen($uid) == 10) {
            return $uid;
        } else if (strlen($uid) == 6) {
            return $uid;
        } else if (strlen($uid) == 12 && intval(substr($uid, 0, 2)) > 18) {
            return substr($uid, 2);
        }
        return false;
    }
    
    
    function get($app, $params) {
        switch ($params['id']) {
            case 'create':
                $data = $this->app->get('SESSION.tmpdata');
                if (!is_array($data)) $this->app->error(404);
                $this->slots['pagetitle'] = 'Konto';
                $this->slots = array_merge($this->slots, $data);
                $this->setPage('user-create');
                break;
                
            case 'new':
                $user = new \models\User(false, false, true);
                $user->save();
                $id = $user->data->id;
                $app->set('SESSION.newuser', true);
                $app->reroute('/user/'.$id);
                break;
            
            case 'all':
                $this->menu = true;
                $this->footer = true;
                $this->addPiece('main', 'tablesorter', 'extrahead');
                $ids = self::getIDs();
                if ($this->hasLevel(4)) {
                    $this->addPiece('main', 'xeditable', 'extrahead');
                }
                $this->slots['pagetitle'] = '{Users}';
                $this->slots['ids'] = count($ids);
                $this->setPage('users');

                $header = \models\User::getHeader($this->lvl);
                $users = self::getUsers(0, 40);
        
                $this->buildTable($header, $users);
                
                break;
            
            default:
                if ($this->lvl < 3) {
                    if ($this->uid !== $params['id']) {
                        $this->app->reroute('/noaccess');
                    }
                }
                $this->menu = true;
                $this->footer = true;
                $user = new \models\User('id', $params['id']);
                if($user->exists) {
                    $this->slots['id'] = $params['id'];
                    $this->setPage('user');
                    
                    $slots = array('lastname' => '', 'firstname' => '', 'email' => '', 'class' => '', 'uid' => '', 'level' => "1", 'status' => "0");
                    $this->addSlots($slots);
                
                    if ($app->get('SESSION.newuser')) {
                        $app->set('SESSION.newuser', false);
                        
                        $this->newBarcode($user->data);
                        $this->slots['pagetitle'] = 'Ny användare';
                    } else {

                        $copies = \controllers\Copy::getCopies($user->data, $this->lvl);
                        $this->addSlots($user->getData());
                        $this->slots['pagetitle'] = $this->slots['user'];
                        $this->addPiece('main', 'tablesorter', 'extrahead');
                        if (count($copies)) {
                            $header = \models\Copy::getHeader($this->lvl, 'user');
                            $this->buildTable($header, $copies);
                        }
                    }
                    if ($this->hasLevel(4)) {
                        $this->addPiece('main', 'xeditable', 'extrahead');
                        $this->addPiece('page', 'editing', 'editing');
                    }
                    
                } else {
                    $app->reroute('/user/all');
                }
                break;
        }
    }
    
    function post($app, $params) {
        if ($params['id'] == 'create' && $this->app->get('POST.email')) {
        
            $lastname = $this->app->get('POST.lastname');
            
            $user = new \models\User('email', $this->app->get('POST.email'));

            $uid = $this->app->get('POST.uid');
            $uid = $this->reformatUID($uid);

            $salt = self::salt();
            $data = array('email' => $this->app->get('POST.email'),
                          'firstname' => $this->app->get('POST.firstname'),
                          'lastname' => $this->app->get('POST.lastname'),
                          'uid' => $uid,
                          'password' => self::hash($this->app->get('POST.password'), $salt),
                          'salt' => $salt,
                          'level' => 1,
                          'status' => 1);
                          
            if ($user->exists) {
                unset($data['level']);
                $user->update($data);
                $user->save();
            } else {
                $user = \R::findOne('user', ' uid = :uid AND lastname LIKE :lname ', array('uid' => substr($uid, 0 , 6), 'lname' => $lastname.'%'));
                if ($user) {
                    $user->import($data);
                    \R::store($user);
                }
                $id = $user->id;
                $user = new \models\User('id', $id);
            }
            
            $app->set('SESSION.user', $user->info());
            $app->reroute('/');
        } else {
            $this->reqLevel(3);
            $this->tpl = false;
        
            $field = $app->get('POST.name');
            $id = $app->get('POST.pk');
            $value = $app->get('POST.value');
            
            $user = new \models\User('id', $id);
            
            if ($field == 'bc_print') {
                if ($value) {
                    $this->newBarcode($user->data);
			    } else {
			        $barcode = \R::relatedOne($user->data, 'barcode');
			        if ($barcode) \R::trash($barcode);
			    } 
			}
            else if ($value == '' || $value[0] == '[') {
                echo "Ogiltigt värde";
            } else {
                $user->update(array($field => $value));
                $user->save();
            }
        }
    }
    
    function newBarcode($user) {
        $barcode = \R::dispense('barcode');
        $barcode->barcode = $user->barcode;
		$barcode->text = $this->formatName($user);
		$barcode->type = 'user';
		$barcode->b_id = $user->id;
		\R::store($barcode);
		\R::associate($barcode, $user);
    }
    
    function formatName($user) {
        $name = $user->lastname.', '.$user->firstname.' ('.$user->class.')';
        return $name;
    }
    
    function put() {}
    
    function delete($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        
        $user = new \models\User('id', $params['id']);
        $data = serialize($user->data->export());
        
		$barcode = \R::relatedOne($user->data, 'barcode');
		if ($barcode) \R::trash($barcode);
			        
        new \controllers\Log('delete', 'Deleted user "'. $user->data->email.'"', $data);

        $user->delete();

        echo $app->get('BASE').'/user/all';
    }
    
    static function getIDs() {
        $rows = \R::getAll('SELECT id FROM user ORDER BY class, lastname, firstname');
		return array_map(function($a) { return $a['id']; }, $rows);
    }
    
    static function getUsers($from=0, $to=false) {
        $ids = self::getIDs();
        $users = array();
        $length = ($to !== false)? $to-$from : NULL;
        $ids = array_slice($ids, $from, $length);
        foreach($ids as $id) {
            $t = \R::load('user', $id);
            $user = $t->export();
            $user['bc_print'] = \R::relatedOne($t, 'barcode')? 1 : 0;
            $user['books'] = count($t->ownCopy);
            $users[] = $user;
        }
        
		return $users;
    }
}