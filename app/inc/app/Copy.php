<?php

/**
 * Copy controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */ 
/* Model: Title
    id [auto]
    title [Model.Title]
    user [Model.User]
    return_date [date]
    collection [Model.Collection]
    barcode [str]
*/

namespace app;

class Copy extends \app\Controller {
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
                if ($value) {
                    $user = \R::load('user', $value);
                    $user->ownCopy[] = $copy;
                    Title::updateBorrowed($copy->title);
                    \R::store($user);
                } else {
                    unset($copy->user);
                    unset($copy->return_date);
                    Title::updateBorrowed($copy->title);
                    \R::store($copy);
                }
                
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
            if ($lvl > 2) {
                $cop['return'] = '<span class="glyphicon glyhpicon-return"></span>';
            }
            
            $copies[] = $cop;
        }
        return $copies;
    }
    
    function put() {}
    function delete() {}
    
    static function getHeader($lvl, $for='title') {
        if ($for == 'title') {
            $header = array(
                'collection' => array(
                    'class' => 'group-word filter-false', 'name' => '{Collection}'),
                'barcode' => array(
                    'class' => 'group-false', 'name' => '{Barcode}'),
                'borrowed_by' => array(
                    'class' => 'group-false', 'name' => '{Borrowed}', 'href' => 'user', 'uid' => true),
                'return_date' => array(
                    'class' => 'group-false', 'name' => '{Return date}'),
                'bc_print' => array(
                    'class'=>'sorter-false', 'name'=>'', 'icon'=>'barcode')
            );
       
            if ($lvl < 2) {
                unset($header['borrowed_by']);
                unset($header['bc_print']);
            }
        } else if ($for == 'user') {
            $header = array(
                'collection' => array(
                    'class' => 'group-word filter-false', 'name' => '{Collection}'),
                'title' => array(
                    'class' => 'group-false', 'name' => '{Title}', 'href' => 'title', 'tid' => true),
                'barcode' => array(
                    'class' => 'group-false', 'name' => '{Barcode}'),
                
                'return_date' => array(
                    'class' => 'group-false', 'name' => '{Return date}'),
                'return' => array(
                    'class' => 'group-false filter-false sorter-false', 'name' => '', 'icon' => 'repeat', 'href' => 'copy'),
            );
            if ($lvl < 3) {
                unset($header['return']);
            }
        }
        return $header;
    }
    
}