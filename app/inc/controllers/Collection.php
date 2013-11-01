<?php

/**
 * Collection controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;
require_once('app/lib/rb.php');
\R::setup('sqlite:data/db.db');

class Collection extends \controllers\Controller {
    
    static function getCollections() {
        $colls = \R::findAll('collection');
        $collections = array();
        foreach($colls as $coll) {
            $collection = $coll->export();
            if ($coll['type'] == 'fixed') {
                $collection['value'] = json_decode($collection['value']);
            }
            $collections[] = $collection;
        }
        return $collections;
    
    }
    
    function get($app, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    function post($app, $params) {
        $name = $app->get('POST.name');
        $value = $app->get('POST.value');
        $id = $app->get('POST.pk');
        
        $copy = \R::load('collection', $id);
        
        switch($name) {
            case 'borrowed_by':
                $user = \R::load('user', $value);
                
                $user->ownCopy[] = $copy;
                \models\Title::updateBorrowed($copy->title);
                \R::store($user);
                break;
            
            case 'bc_print':
                if ($value) {
                    self::newBarcode($copy);
			    } else {
			        $barcode = \R::relatedOne($copy, 'barcode');
			        if ($barcode) \R::trash($barcode);
			    }  
			          
			default:
			    $copy->import(array($name => $value));
			    \R::store($copy);
			    break;
        }
    }
    
    static function newBarcode($object, $type, $text, $id) {
        $barcode = \R::dispense('barcode');
        $barcode->barcode = $object->barcode;
		$barcode->text = $text;
		$barcode->type = $type;
		$barcode->b_id = $id;
		\R::store($barcode);
		\R::associate($barcode, $object);
    }
    
    function put() {}
    function delete() {}
}