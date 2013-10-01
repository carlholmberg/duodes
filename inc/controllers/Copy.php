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
    function get($f3, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    function post() {}
    function put() {}
    function delete() {}
}