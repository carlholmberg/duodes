<?php

/**
 * Ajax controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Ajax extends \app\Controller {
    function get($app, $params) {
        switch ($params['what']) {
        
            case 'tablesorter-nav':
                echo file_get_contents($app->get('UI').'tablesorter-nav.html');
                break;
            
            case 'collections':
                $collections = Collection::getCollections();
                $out = array();
                foreach($collections as $coll) {
                    $out[] = sprintf("{\"%s\": \"%s\"}", $coll['id'], $coll['name']);
                }
                
                echo "[\n".implode(",\n", $out)."\n]";
                break;
            
            case 'collections-opt':
                $collections = Collection::getCollections();
                foreach($collections as $coll) {
                    printf("<option value=\"%s\">%s</option>", $coll['id'], $coll['name']);
                }

                break;
            
            case 'collections-fixed':
                $coll = \R::findOne('collection', ' type = "fixed" ');
                $data = unserialize($coll->value);
                
                foreach($data as $val) {
                    if ($app->get('SESSION.FixedColl') == $val['ts']) {
                        printf("<option selected='selected' value=\"%s\">%s (%s)</option>", $val['ts'], $val['name'], $val['ts']);
                    } else {
                        printf("<option value=\"%s\">%s (%s)</option>", $val['ts'], $val['name'], $val['ts']);
                    }
                }

                break;
            
            case 'users':
                $users = User::getUsers();
                $out = array();
                foreach($users as $user) {
                    $name = $user['lastname'].', '.$user['firstname'].' ('.$user['class'].')';
                    $out[] = sprintf("{\"%s\": \"%s\"}", $user['id'], $name);
                }
                echo "[\n".implode(",\n", $out)."\n]";
                break;
                
            case 'class':
                $rows = \R::getCol('SELECT DISTINCT class FROM user ORDER BY class');
                foreach($rows as $row) {
                    printf("<option value=\"%s\">%s</option>", $row, $row);
                }
                break;
                
            case 'codes':
                $rows = \R::getCol('SELECT DISTINCT code FROM title ORDER BY code');
                foreach($rows as $row) {
                    printf("<option value=\"%s\">%s</option>", $row, $row);
                }
                break;
        }
    }
    function post($app, $params) {
        
        switch ($params['what']) {
        
            case 'setcollfixed':
                $app->set('SESSION.FixedColl', $app->get('POST.val'));
                break;
        }
    }
    function put() {}
    function delete() {}
    
}
