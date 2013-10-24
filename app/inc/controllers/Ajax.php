<?php

/**
 * Ajax controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Ajax extends \controllers\Controller {
    function get($app, $params) {
        if ($params['what'] == 'titleids') {
            echo count(\models\Title::getIDs());
        } else if ($params['what'] == 'tablesorter-nav') {
            echo file_get_contents($app->get('UI').'tablesorter-nav.html');
        }
    }
    function post($app, $params) {
        
    }
    function put() {}
    function delete() {}
    
}
