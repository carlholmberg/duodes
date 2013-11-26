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
        if (isset($params['id']) && $params['id'] == 'new') {
            $c = \R::dispense('collection');
            \R::store($c);
            $app->reroute('/collection');
        }
    
    
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Collections}';
        $this->setPage('collection');
        $this->addPiece('main', 'xeditable', 'extrahead');
        
        $coll = \R::findAll('collection');
        
        foreach($coll as $c) {
            $data = $c->export();
            $data['type_source'] = '{"fixed": "Datum", "days": "Dagar", "ref": "Inget"}';
            if ($data['type'] == 'fixed') $data['value'] = self::toText($data['value']);
            $this->addPiece('page', 'row', 'row', $data);
        }
        
        
    }
    
    static function toText($arr) {
        $text = '';
        foreach(unserialize($arr) as $entry) {
            $text .= $entry['name'].'='.$entry['ts'];
            if (isset($entry['default'])) $text .= '*';
            $text .= "\n";
        }
        return $text;
    }
    
    
    static function toArr($text) {
        $arr = array();
        $hasdef = false;
        foreach(explode("\n", trim($text)) as $entry) {
            $default = false;
            list($name, $ts) = explode('=', trim($entry));
            if (strpos($ts, '*')) {
                $ts = str_replace('*', '', $ts);
                if (!$hasdef) {
                    $default = true;
                    $hasdef = true;
                }
            }
            $e = array('name' => $name, 'ts' => $ts);
            if ($default) $e['default'] = true;
            $arr[] = $e;
        }
        return serialize($arr);
    }
    
    
    function post($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        
        $field = $app->get('POST.name');
        $id = $app->get('POST.pk');
        $value = $app->get('POST.value');
        $coll = \R::load('collection', $id);
        if ($coll) {
            if ($field == 'value' && $coll->type == 'fixed') {
                $value = self::toArr($value);
            }
            if ($field == 'value' && $coll->type == 'ref') {
                if ($value != '0') {
                    $value = 0;
                }
            }
            $coll->import(array($field => $value));
            \R::store($coll);
        }
    }
    
    
    function delete($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        
        $coll = \R::load('collection', $params['id']);
        if ($coll) {
            $this->addMessage('coll_del', array('name'=>$coll->name));
            $data = serialize($coll->export());
            new Log('delete', 'Deleted collection "'. $coll->name.'"', $data);
            \R::trash($coll);
            \R::exec( 'update copy set collection_id=NULL where collection_id='.$params['id'] );
        }

        echo $app->get('BASE').'/collection';
    }
    
}