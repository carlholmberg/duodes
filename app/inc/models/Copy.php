<?php

/**
 * Copy model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Copy extends \models\DBModel {
    /* Model: Title
        id [auto]
        title [Model.Title]
        user [Model.User]
        return_date [date]
        collection [Model.Collection]
        barcode [str]
    */
    
    function __construct($type, $val, $create=false) {
        if ($create) {
            $this->load('copy');
        } else if ($type == 'id') {
            $this->load('user', intval($val));
        } else {
            $this->data = \R::findOne('copy', ' '.$type.' = :val', 
                array(':val' => $val)
            );
        }
        $this->exists = false;
        if ($this->data) {
            $this->exists = true;
            $this->active = (bool)$this->data->status;
        }
    }
    
    function returnCopy() {
        unset($this->data->user);
        \models\Title::updateBorrowed($this->data->title);        
        $this->save();
    }
    
    
    static function getHeader($lvl, $for='title') {
        if ($for == 'title') {
            $header = array(
                'collection' => array(
                    'class' => 'group-word filter-false', 'placeholder' => '', 'name' => '{Collection}'),
                'barcode' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Barcode}'),
                'borrowed_by' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Borrowed}', 'href' => 'user', 'uid' => true),
                'return_date' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Return date}'),
                'bc_print' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Print barcode}')
            );
       
            if ($lvl < 2) {
                unset($header['borrowed_by']);
                unset($header['bc_print']);
            }
        } else if ($for == 'user') {
            $header = array(
                'collection' => array(
                    'class' => 'group-word filter-false', 'placeholder' => '', 'name' => '{Collection}'),
                'title' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Title}', 'href' => 'title', 'tid' => true),
                'barcode' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Barcode}'),
                
                'return_date' => array(
                    'class' => '', 'placeholder' => '', 'name' => '{Return date}'),
            );
        }
        return $header;
    }
}