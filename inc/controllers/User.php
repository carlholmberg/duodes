<?php

/**
 * User controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class User extends \controllers\Controller {
    function get($f3, $params) {
        echo 'User (GET): name '.$params['id'];
    }
    function post() {}
    function put() {}
    function delete() {}
}