<?php

/**
 * Copy controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Copy extends \controllers\Controller {
    function get($app, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    function post($app, $params) {
        $name = $params['name'];
        $value = $params['value'];
        $id = $params['pk'];
        
        trigger_error(var_dump($_POST), E_USER_ERROR);
    }
    function put() {}
    function delete() {}
}