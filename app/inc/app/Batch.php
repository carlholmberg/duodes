<?php

/**
 * Batch controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Batch extends \app\ViewController {
    
    function get($app, $params) { 
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = 'Batch';
        $this->setPage('batch');
    }
    
    function post($app, $params) {
    }
    
    function put() {}
    function delete() {}
}