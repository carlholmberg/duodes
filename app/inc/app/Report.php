<?php

/**
 * Report controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Report extends \app\Controller {

    function get($f3, $params) {
        if (isset($params['name'])) {
            if (isset($params['collection'])) {
                echo 'Report (GET): name '.$params['name'].' for '.$params['collection'];
            } else {
                echo 'Report (GET): name '.$params['name'];
            }
        } else {
            echo 'Show all reports';
        }

    }
    function post() {}
    function put() {}
    function delete() {}
}