<?php

/**
 * Main Database Model class
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

require_once('../lib/rb.php');
\R::setup('sqlite:../data/db.db');

class DBModel
{

    public $data;
    public $type;
    
    function __construct($type, $id) {
        $this->type = $type;
        if ($id == false) {
            $this->data = \R::dispense($type);
        } else {
            $this->data = \R::load($type, $id);
        }
        return $this->data;
    }
    
    function save() {
        \R::store($this->data);
    }
    
}
