<?php

/**
 * User controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
/* Model: User
    id [auto]
    email [str]
    firstname [str]
    lastname [str]
    class [str]
    uid [str]
    level [0-4] ([(guest), circ, student, teacher, admin])
    salt [salt()]
    password [hash(str, salt)]
    status [1: active, 0: inactive]
*/
    
namespace app;

class User extends \app\ViewController {
    
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
        $user = \R::findOne('user', ' email = ? ', array($email));
        
        if ($user) {
            if ($user->active) {
                return $this->info($user);
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
        $user = \R::findOne('user', ' email = ? ', array($email));
        
        if ($user) {
            if (self::verify($data['password'], $user->password, $user->salt)) {
                // TMP
                if ($user->email == 'carl.holmberg@kunskapsgymnasiet.se') {
                    $user->level = 4;
                    \R::store($user);
                }
                // --TMP
                return $this->info($user);
            }
            return false;
        }
        return false;
    }
    
    function info($user) {
        return array('firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'level' => $user->level,
                'id' => $user->id);
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
                $user = \R::dispense('user');
                $id = \R::store($user);
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

                $header = self::getHeader($this->lvl);
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
                $user = \R::load('user', $params['id']);
                if($user) {
                    $this->slots['id'] = $params['id'];
                    $this->setPage('user');
                    
                    $slots = array('lastname' => '', 'firstname' => '', 'email' => '', 'class' => '', 'uid' => '', 'level' => "1", 'status' => "0");
                    $this->addSlots($slots);
                
                    if ($app->get('SESSION.newuser')) {
                        $app->set('SESSION.newuser', false);
                        
                        $this->newBarcode($user);
                        $this->slots['pagetitle'] = 'Ny användare';
                    } else {

                        $copies = Copy::getCopies($user, $this->lvl);
                        $this->addSlots($user->export(false, false, true));
                        $this->slots['pagetitle'] = $this->slots['user'];
                        $this->addPiece('main', 'tablesorter', 'extrahead');
                        if (count($copies)) {
                            $header = Copy::getHeader($this->lvl, 'user');
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
        if ($params['id'] == 'create' && $tapp->get('POST.email')) {
        
            $lastname = $app->get('POST.lastname');
            
            $user = \R::findOne('user', ' email = ? ', array($app->get('POST.email')));
            
            $uid = $app->get('POST.uid');
            $uid = $this->reformatUID($uid);

            $salt = self::salt();
            $data = array('email' => $app->get('POST.email'),
                          'firstname' => $app->get('POST.firstname'),
                          'lastname' => $app->get('POST.lastname'),
                          'uid' => $uid,
                          'password' => self::hash($app->get('POST.password'), $salt),
                          'salt' => $salt,
                          'level' => 1,
                          'status' => 1);
                          
            if ($user) {
                unset($data['level']);
                $user->import($data);
                \R::store($user);
            } else {
                $user = \R::findOne('user', ' uid = :uid AND lastname LIKE :lname ', array('uid' => substr($uid, 0 , 6), 'lname' => $lastname.'%'));
                if ($user) {
                    $user->import($data);
                    \R::store($user);
                }
            }
            
            $app->set('SESSION.user', $this->info($user));
            $app->reroute('/');
        } else {
            $this->reqLevel(3);
            $this->tpl = false;
        
            $field = $app->get('POST.name');
            $id = $app->get('POST.pk');
            $value = $app->get('POST.value');
            
            $user = \R::load('user', $id);
            
            if ($field == 'bc_print') {
                if ($value) {
                    $this->newBarcode($user);
			    } else {
			        $barcode = \R::relatedOne($user, 'barcode');
			        if ($barcode) \R::trash($barcode);
			    } 
			}
            else if ($value == '' || $value[0] == '[') {
                echo "Ogiltigt värde";
            } else {
                $user->import(array($field => $value));
                \R::store($user);
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

        $user = \R::load('user', $params['id']);
        $data = serialize($user->export());
        
		$barcode = \R::relatedOne($user, 'barcode');
		if ($barcode) \R::trash($barcode);
			        
        new \app\Log('delete', 'Deleted user "'. $user->email.'"', $data);

        \R::trash($user);

        echo $app->get('BASE').'/user/all';
    }
    
    static function getIDs() {
        $rows = \R::getCol('SELECT id FROM user ORDER BY class, lastname, firstname');
		return $rows;
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
    
    static function getHeader($lvl) {
        $header = array(
            'class'=>array(
                'class'=>'group-letter-3', 'name'=>'{Class}'),
            'lastname'=>array(
                'class'=>'', 'name'=>'{Lastname}', 'href' => 'user', 
                'uid' => true),
            'firstname'=>array(
                'class'=>'', 'name'=>'{Firstname}', 'href' => 'user', 
                'uid' => true),
            'level'=>array(
                'class'=>'', 'name'=>'{Level}'),
            'status'=>array(
                'class'=>'', 'name'=>'{Status}'),
            'books'=>array(
                'class'=>'', 'name'=>'{Borrowed books}'),
            'bc_print'=>array(
                'class'=>'', 'name'=>'{Print barcode}'));
        
        if ($lvl < 3) {
            unset($header['level']);
            unset($header['bc_print']);
        }
        return $header;
    }    
}