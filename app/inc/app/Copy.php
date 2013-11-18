<?php

/**
 * Copy controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
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

class Copy extends Controller {
    function get($app, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    
    function post($app, $params) {
        if ($app->get('POST.action')) {
            switch ($app->get('POST.action')) {
                case 'return':
                    $copy = \R::load('copy', $params['id']);
                    if ($copy) {
                        $collection = ($copy->collection)? $copy->collection->name : '';
                        if ($copy->user) {
                            $user = $copy->user;
                            $this->addMessage('circ_r_ok', array(
                                'fname'=>$user->firstname, 'lname'=>$user->lastname,
                                'coll'=>$collection, 'title'=>$copy->title->title,
                            'bc'=>$copy->barcode));
                            self::returnCopy($copy);
                        }
                    
                    }
                    return;
            }
        }
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
                    self::returnCopy($copy);
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
    
    static function returnCopy($copy) {
        unset($copy->user);
        unset($copy->return_date);
        Title::updateBorrowed($copy->title);
        \R::store($copy);
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
                $cop['return'] = '{Return}';
                $cop['delete'] = '{Delete}';
            }
            
            $copies[] = $cop;
        }
        return $copies;
    }
    
    function put() {}
    function delete($app, $params) {
        $copy = \R::load('copy', $params['id']);
        if ($copy) {
            $data = serialize($copy->export());
            new Log('delete', 'Deleted copy "'. $copy->barcode.'" for '.$copy->title->title, $data);
            $this->addMessage('copy_del');
            \R::trash($copy);
        }
    
    
    }
    
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
                    'class'=>'sorter-false', 'name'=>'', 'icon'=>'barcode'),
                'delete' => array(
                    'class' => 'group-false filter-false sorter-false', 'name' => '', 'row-icon' => 'trash', 'href' => 'copy'),
            );
       
            if ($lvl < 2) {
                unset($header['borrowed_by']);
                unset($header['bc_print']);
                unset($header['delete']);
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
                    'class' => 'group-false filter-false sorter-false', 'name' => '', 'row-icon' => 'repeat', 'href' => 'copy'),
            );
            if ($lvl < 3) {
                unset($header['return']);
            }
        }
        return $header;
    }
    
}