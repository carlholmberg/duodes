<?php

/**
 * Collection controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
/* Model: Colection
    id [auto]
    name [str]
    type [str] (fixed/days/ref)
    value [str]
*/
    
namespace app;

class Collection extends ViewController {
    
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
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Collections}';
        $this->setPage('collection');
        $this->addPiece('main', 'xeditable', 'extrahead');
        
        $coll = \R::findAll('collection');
        
        foreach($coll as $c) {
            $data = $c->export();
            $data['type_source'] = '{"fixed": "Datum", "days": "Dagar", "ref": "Inget"}';
            $this->addPiece('page', 'row', 'row', $data);
        }
        
        
    }
    
    function post($app, $params) {
       
    }
}