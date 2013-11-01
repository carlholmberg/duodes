<?php

/**
 * Copy controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;
require_once('app/lib/rb.php');
\R::setup('sqlite:data/db.db');

class Copy extends \controllers\Controller {
    function get($app, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    function post($app, $params) {
        $name = $app->get('POST.name');
        $value = $app->get('POST.value');
        $id = $app->get('POST.pk');
        
        $copy = \R::load('copy', $id);
        
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
			    break;
			
			case 'collection':
                $coll = \R::load('collection', $value);
                $copy->collection = $coll;
                \R::store($copy);
                break;			        
			 
			default:
			    $copy->import(array($name => $value));
			    \R::store($copy);
			    break;
        }
    }
    
    static function newBarcode($copy) {
        $barcode = \R::dispense('barcode');
        $barcode->barcode = $copy->barcode;
		$barcode->text = $copy->title->title;
		$barcode->type = 'copy';
		$barcode->b_id = $copy->title->id;
		\R::store($barcode);
		\R::associate($barcode, $copy);
    }
    
    
    static function getCopies($for, $lvl) {
        $copies = array();
        $defaults = array_fill_keys(array('return_date', 'title', 'uid', 'tid', 'borrowed_by', 'bc_print', 'barcode', 'collection'), '');
        foreach($for->ownCopy as $c) {
            $cop = array_merge($defaults, $c->export(false, false, true));
            if ($cop['collection_id']) $cop['collection'] = $c->collection->name;
            $cop['title'] = $c->title->title;
            if ($lvl > 2) {
                if ($cop['user_id']) {
                    if ($cop['return_date']) {
                        $cop['return_date'] = date('Y-m-d', $cop['return_date']);
                    }
                    $user = $c->user;
                    $name = $user->lastname.', '.$user->firstname.' ('.$user->class.')';
                    $cop['borrowed_by'] = $name;
                    $cop['uid'] = $c->user->id;
                    $cop['tid'] = $c->title->id;
                }
            }
            if ($lvl == 4) {
                $cop['bc_print'] = \R::relatedOne($c, 'barcode')? 1 : 0;
            }
            
            $copies[] = $cop;
        }
        return $copies;
    }
    
    function put() {}
    function delete() {}
}