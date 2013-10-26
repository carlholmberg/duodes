<?php

/**
 * Log model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Log extends \models\DBModel {
    
    function __construct($data) {
        $this->load('log');
        $this->update($data);
        $this->save();
    }
}