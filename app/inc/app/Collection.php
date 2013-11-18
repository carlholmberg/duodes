<?php

/**
 * Collection controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Collection extends Controller {
    
    static function getCollections() {
        $colls = \R::findAll('collection');
        $collections = array();
        foreach($colls as $coll) {
            $collection = $coll->export();
            if ($coll['type'] == 'fixed') {
                $collection['value'] = unserialize($collection['value']);
            }
            $collections[] = $collection;
        }
        return $collections;
    
    }
    
    function get($app, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    
    function post($app, $params) {
        
    }
    
    function put() {}
    function delete() {}
}