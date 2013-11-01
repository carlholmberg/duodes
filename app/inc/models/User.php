<?php

/**
 * User model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class User extends \models\DBModel {
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
    
    function __construct($type, $val, $create=false) {
        if ($create) {
            $this->load('user');
        } else if ($type == 'id') {
            $this->load('user', intval($val));
        } else {
            $this->data = \R::findOne('user', ' '.$type.' = :val', 
                array(':val' => $val)
            );
        }
        $this->exists = false;
        if ($this->data) {
            $this->exists = true;
            $this->active = (bool)$this->data->status;
        }
    }

    
    function borrow($copy) {
        $this->data->ownCopy[] = $copy->data;
        \models\Title::updateBorrowed($copy->data->title);
        $this->save();
    }
    
    function getData() {
        $data = array();
        return $this->data->export(false, false, true);
        if ($this->data) {
            foreach($this->data->export() as $key=>$val) {
                if (is_string($val)) {
                    $data[$key] = $val;
                }
                if ($val == null) {
                    $data[$key] = '';
                }
            }
        }
        return $data;
    }
    
    
    function createUser() {
        
    }
    
    static function getIDs() {
        $rows = \R::getAll('SELECT id FROM user ORDER BY class, lastname, firstname');
		return array_map(function($a) { return $a['id']; }, $rows);
    }
    
    function info() {
        if ($this->data) {
            return array('firstname' => $this->data->firstname,
                         'lastname' => $this->data->lastname,
                         'email' => $this->data->email,
                         'level' => $this->data->level,
                         'id' => $this->data->id);
        }
        return false;
    }
    
    static function getHeader($lvl) {
        $header = array(
            'class'=>array(
                'class'=>'group-letter-3', 'placeholder'=>'', 'name'=>'{Class}'),
            'lastname'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Lastname}', 'href' => 'user', 
                'uid' => true),
            'firstname'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Firstname}', 'href' => 'user', 
                'uid' => true),
            'level'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Level}'),
            'status'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Status}'),
            'books'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Borrowed books}'),
            'bc_print'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Print barcode}'));
        
        if ($lvl < 3) {
            unset($header['level']);
            unset($header['bc_print']);
        }
        return $header;
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