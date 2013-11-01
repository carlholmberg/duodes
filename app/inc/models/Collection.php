<?php

/**
 * Collection model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Collection extends \models\DBModel {
    /* Model: Collection
        id [auto]
        name [str]
        type [str] ([fixex, days, ref])
        value [str]
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
}