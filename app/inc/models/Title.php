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
        $this->load('title', $id);
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
    
    static function updateBorrowed($title) {
	    $borrowed = 0;
	    foreach($title->ownCopy as $copy) {
	        if ($copy->user) {
	            $borrowed += 1;
	        }
	    }
	    \R::store($title);
	}
    
    function getData() {
        $data = array();
        return $this->data->export(false, false, true);
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
    
    static function getHeader($lvl) {
        $header = array(
            'title'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Title}', 'href'=>'title'),
            'author'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Author}'),
            'isbn'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'ISBN'),
            'date'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Year}'),
            'code'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Subject}'),    
            'total'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Total}'),
            'borrowed'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Borrowed}'));
        if ($lvl < 2) {
            unset($header['borrowed']);
        }
        return $header;
    }
    
    function deleteCopies() {
        foreach($this->data->ownCopy as $copy) {
            \R::trash($copy);
        }
    }
    
    function addCopies($n, $c_id) {
        $title = &$this->data;
        $copies = \R::dispense('copy', $n);
		$barcodes = \R::dispense('barcode', $n);
        $coll = \R::load('collectoin', $c_id);
        
		if (!is_array($copies)) {
			$copies = array($copies);
			$barcodes = array($barcodes);
		}

		foreach($copies as $i => $copy) {
			$copy->barcode = '';
			$copy->title = $title;
			$copy->collection = $coll;
			$c_id = \R::store($copy);
			$copy->barcode = strtoupper(base_convert($title->id.$c_id, 10, 36)); 
		
			$barcodes[$i]->barcode = $copy->barcode;
			$barcodes[$i]->text = $title->title;
			$barcodes[$i]->type = 'copy';
			$barcodes[$i]->b_id = $title->id;
			
			\R::associate($barcodes[$i], $copy);
			$title->ownCopy[] = $copy;
		}
		$title->total = count($title->ownCopy);
		\R::storeAll($barcodes);
		\R::storeAll($copies);
		$this->save();
    }
}