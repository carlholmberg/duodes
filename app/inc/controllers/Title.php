<?php

/**
 * Title controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Title extends \controllers\Controller {
    function get($f3, $params) {
        echo $params['collection']."<br>";
        switch ($params['id']) {
            case 'all':
                echo 'Show all titles';
                break;
            case 'new':
                echo 'New title';
                break;
            default:
                if (!is_numeric($params['id']))
                    $f3->reroute('/'.$params['collection']);
                echo 'Get title:'.$params['id'];
                break;
        }
    }
    function post() {}
    function put() {}
    function delete() {}
}
