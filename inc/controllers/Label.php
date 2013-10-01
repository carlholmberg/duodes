<?php

/**
 * Label controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Label extends \controllers\Controller {
    function get($f3, $params) {
        echo '('.$params['collection'].') Item (GET): type '.$params['type'].' id '.$params['id'];
        if ($params['type'] == 'user') {
            if (isset($params['group'])) {
                
            }
        } else if ($params['type'] == 'copy') {
            if ($params['id'] == 'selected') {
                echo 'Get labels for selected copies';
            } else if (is_numeric($params['id'])) {
                echo 'Get label for '.$params['id'];
            } else {
                $f3->reroute('/'.$params['collection']);
            }
        } else {
            $f3->reroute('/'.$params['collection'].'/label');
        }
    }
    function post() {}
    function put() {}
    function delete() {}
}