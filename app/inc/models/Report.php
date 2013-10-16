<?php

/**
 * Report model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Report extends \models\DBModel {
    
    function __construct($id=false) {
        $report = parent::__construct('report', $id);
        
        
        $this->save();

        die(var_dump($this->data));
    }
}