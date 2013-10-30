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
        switch ($params['what']) {
        
            case 'tablesorter-nav':
                echo file_get_contents($app->get('UI').'tablesorter-nav.html');
                break;
            
            case 'collections':
                echo '{"Bibliotek": "Bibliotek", "Kurslitteratur": "Kurslitteratur", "WS-litteratur" : "WS-litteratur", "Referenslitteratur" : "Referenslitteratur"}';
                break;
            case 'users':
                $users = \models\User::getUsers();
                $out = array();
                foreach($users as $user) {
                    $name = $user['lastname'].', '.$user['firstname'].' ('.$user['class'].')';
                    $out[] = sprintf("{\"%s\": \"%s\"}", $user['id'], $name);
                }
                echo "[\n".implode(",\n", $out)."\n]";
                break;
        }
    }
    function post($app, $params) {
        
    }
    function put() {}
    function delete() {}
    
}
