<?php

/**
 * Main controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Main extends \controllers\Controller {
    function index() {
        echo 'Index page';
    }
    
    function collection($f3, $params) {
        echo 'Start for '.$params['collection'];
    }
    
    function label($f3, $params) {
        echo 'Label-page';
    }
    
    function copy($f3, $params) {
        echo 'Copy-page';
    }

    function title($f3, $params) {
        echo 'Title-page';
    }
    
    function user($f3, $params) {
        echo 'User-page';
    }
    
    function report($f3, $params) {
        echo 'Report-page';
    }
}