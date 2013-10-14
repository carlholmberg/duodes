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
    
    function minify($app, $params) {
        $app->set('UI', $params['type'] . '/');
        echo \Web::instance()->minify($_GET['files']);
    }

    function index($app, $params) {
        //$app->set('page', 'index');
        //$template = \Template::instance();
        //echo $template->render('index.html');
//        echo \View::instance()->render('index.html');
    }
    
    function collection($app, $params) {
        echo 'Start for '.$params['collection'];
    }
    
    function label($app, $params) {
        echo 'Label-page';
    }
    
    function copy($app, $params) {
        echo 'Copy-page';
    }

    function title($f3, $params) {
        echo 'Title-page';
    }
    
    function user($app, $params) {
        echo 'User-page';
    }
    
    function report($app, $params) {
        echo 'Report-page';
    }
}