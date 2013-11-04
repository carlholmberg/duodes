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
            $this->data = \R::findOne('user', ' '.$type.' = ?', 
                array($val)
            );
        }
        $this->exists = false;
        if ($this->data) {
            $this->exists = true;
            $this->active = (bool)$this->data->status;
        }
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
    
    
}