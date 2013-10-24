<?php

/**
 * Title model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Title extends \models\DBModel {
    /* Model: Title
        id [auto]
        title [str]
        authors [Model.Author][]
        isbn [str]
        lang [str]
        publisher [str]
        date [int] 
        code [str]
        url [str]
        desc [str]
        keywords [str]
        copies [Model.Copy][]
        total [int]
        borrowed [int]
    */
    function __construct($id=false) {
        if ($id) {
            $this->load('title', $id);
        } else {
            return false;
        }
    }
    
    function getCopies() {
        $copies = array();
        foreach($this->data->ownCopy as $c) {
            $copies[] = $c->export(false, false, true);
        }
        return $copies;
    }
    
    static function getIDs() {
        $rows = \R::getAll('SELECT id FROM title ORDER BY author, title');
		return array_map(function($a) { return $a['id']; }, $rows);
    }
    
    static function getTitles($from=0, $to=false) {
        $ids = self::getIDs();
        $titles = array();
        $length = ($to !== false)? $to-$from : NULL;
        $ids = array_slice($ids, $from, $length);
        foreach($ids as $id) {
            $t = \R::load('title', $id);
            $titles[] = $t->export(false, false, true);
        }
        
		return $titles;
    }
    
    function getData() {
        $data = array();
        if ($this->data) {
            foreach($this->data->export() as $key=>$val) {
                if (is_string($val)) {
                    $data[$key] = $val;
                }
                if ($val == null) {
                    $data[$key] = '';
                }
            }
        }
        return $data;
    }
}