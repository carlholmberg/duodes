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

require_once('app/lib/rb.php');
\R::setup('sqlite:data/db.db');

class DBModel
{

    public $data;
    public $type;
    public $exists;
    
    function load($type=false, $id=false) {
        $this->app = \Base::instance();
        if ($type === false) {
            $this->exists = false;
            return false;
        }
        $this->type = $type;

        if ($id == false) {
            $this->data = \R::dispense($type);
            $this->exists = true;
        } else {
            $this->data = \R::load($type, $id);
            $this->exists = (!$this->data->id)? false : true;
        }
    }
    
    
    function update($data) {
        if (is_array($data)) {
            $this->data->import($data);
        }
    }
    
    function save() {
        \R::store($this->data);
    }
    
    function delete() {
        \R::trash($this->data);
    }
}
