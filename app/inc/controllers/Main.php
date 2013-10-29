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

class Main extends \controllers\ViewController {
    
    function minify($app, $params) {
        $app->set('UI', $params['type'] . '/');
        echo \Web::instance()->minify($_GET['files']);
    }

    function index($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Home}';
        $this->setPage('index');
    }
    
    function login($app, $params) {
        $this->slots['pagetitle'] = '{Login}';
        $this->setPage('login');
    }
    
    function noaccess($app, $params) {
        $this->slots['pagetitle'] = '{No access}';
        $this->setPage('noaccess');
    }
    
    function e404() {
        $this->setPage('404');
    }
    
    function label($app, $params) {
        echo 'Label-page';
    }
    
    function copy($app, $params) {
        echo 'Copy-page';
    }


    function titles_ajax($app, $params) {
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        $header = \models\Title::getHeader($this->lvl);
        $titles = \models\Title::getTitles($from, $to);
        
        $tpl = $this->buildTable($header, $titles, true);
        
        $this->tpl = false;
        echo $tpl;
    }
    
    function users_ajax($app, $params) {
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        $header = \models\User::getHeader($this->lvl);
        $users = \models\User::getUsers($from, $to);
        
        $tpl = $this->buildTable($header, $users, true);
        
        $this->tpl = false;
        echo $tpl;
    }
        
    function report($app, $params) {
        echo 'Report-page';
    }
}