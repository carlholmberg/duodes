<?php

/**
 * Log controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Log extends \controllers\Controller {
    function get($f3, $params) {
        echo 'Log (GET): date '.$params['date'];
    }
    function post() {}
    function put() {}
    function delete() {}
}