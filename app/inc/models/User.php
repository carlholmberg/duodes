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
            parent::load('user');
        } else if ($type == 'id') {
            parent::load('user', $val);
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
                'class'=>'', 'placeholder'=>'', 'name'=>'{Lastname}', 'href' => 'user'),
            'firstname'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Firstname}', 'href' => 'user'),
            'level'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Level}'),
            'status'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Status}'),
            'books'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Borrowed books}'),
            'bc_add'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Barcode}'));
        
        if ($lvl < 3) {
            unset($header['level']);
            unset($header['bc_add']);
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
            $user['bc_add'] = false; // TMP
            $user['books'] = count($t->ownCopy);
            $users[] = $user;
        }
        
		return $users;
    }
    
    
    function getCopies() {
        $copies = array();
        foreach($this->data->ownCopy as $c) {
            $cop = $c->export();
            $cop['return_date'] = time(); //tmp
            $cop['return_date'] = date('Y-m-d', $cop['return_date']);
            $cop['title'] = $c->title->title;
            $cop['nid'] = $c->title->id;
            if (!$cop['collection']) $cop['collection'] = 'Kurslitteratur';
            $copies[] = $cop;
        }
        return $copies;
    }
}