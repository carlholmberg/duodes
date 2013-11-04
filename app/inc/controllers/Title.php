<?php

/**
 * Title controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
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

namespace controllers;

class Title extends \controllers\ViewController {

    function get($app, $params) {
        $id = $params['id'];
        
        switch($id) {
            case 'new':
                $title = \R::dispense('title');
                $id = \R::store($title);
                $app->set('SESSION.newtitle', true);
                $app->reroute('/title/'.$id);
                break;
            
            case 'all':
                $this->menu = true;
                $this->footer = true;
                $this->addPiece('main', 'tablesorter', 'extrahead');
                $ids = self::getIDs();
                $this->slots['pagetitle'] = '{Titles}';
                $this->slots['ids'] = count($ids);
                $this->setPage('titles');

                $header = \models\Title::getHeader($this->lvl);
                $titles = Title::getTitles(0, 40);
        
                $this->buildTable($header, $titles);
                
                break;
            
            default:
                $this->menu = true;
                $this->footer = true;
                
                $title = \R::load('title', $id);
                if ($title) {
                    
                    $this->slots['id'] = $id;
                    $this->setPage('title');
                    $slots = array('title' => '', 'author' => '', 'isbn' => '', 'desc' => '', 'keywords' => '', 'publisher' => '',  'date' => '', 'code' => '', 'url' => '', 'registered' => date('Y-m'), 'total' => "0", 'borrowed' => "0");
                    $this->addSlots($slots);
                    
                    if ($app->get('SESSION.newtitle')) {
                        $app->set('SESSION.newtitle', false);
                    
                        $this->slots['pagetitle'] = 'Ny titel';

                    } else {
                        $copies = Copy::getCopies($title, $this->lvl);
                        $data = $title->export(false, false, true);
                        $this->addSlots($data);
                        if (isset($this->slots['title'])) {
                            $this->slots['pagetitle'] = $this->slots['title'];
                        } else {
                            $this->slots['pagetitle'] = 'Namnlös titel';
                        }     
                        $this->addPiece('main', 'tablesorter', 'extrahead');
                        if (count($copies)) {
                            $header = \models\Copy::getHeader($this->lvl);
                            if($this->hasLevel(4)) $this->buildCopyTable($header, $copies);
                            else $this->buildTable($header, $copies);
                        }
                    }
                    if ($this->hasLevel(4)) {
                        $this->addPiece('main', 'xeditable', 'extrahead');
                        $this->addPiece('page', 'editing', 'editing');
                        $this->addPiece('page', 'isbnedit', 'isbnedit');
                    }
                    
                } else {
                    $app->reroute('/title/all');
                }
                break;
        }
    }
    
    
    function post($app, $params) {
        
        $this->reqLevel(3);
        $this->tpl = false;
        if ($app->get('POST.action')) {
            if ($app->get('POST.action') == 'refresh') {
                $isbn = $app->get('POST.isbn');
                $res = Query::Libris($isbn);
                /*if (!$res) {
                    $res = Query::OpenLib($isbn);
                }*/
                $title = \R::load('title', $params['id']);
                if ($title) {
                    if ($res) {
                        $title->import($res);
                        \R::store($title);
                    }
                    echo $app->get('BASE').'/title/'.$params['id'];
                    die();
                } else {
                    echo $app->get('BASE').'/title/all';
                    die();
                }
            }
        }
        
        if (isset($params['action'])) {
            $copies = $app->get('POST.copies');
            if ($copies < 1) {
                $app->reroute('/title/'.$params['id']);
            }
            $collection = $app->get('POST.collection');
            
            $title = \R::load('title', $params['id']);
            if ($title) {
                $this->addCopies($title, $copies, $collection);
            }
                        
            $app->reroute('/title/'.$params['id']);
        }
        
        $field = $app->get('POST.name');
        $id = $app->get('POST.pk');
        $value = $app->get('POST.value');
        
        if ($value == '' || $value[0] == '[') {
            echo "Ogiltigt värde";
            die();
        } else {
            $title = \R::load('title', $id);
            if ($title) {
                $title->import(array($field => $value));
                \R::store($title);
            }
        }
    }
    
    
    function addCopies($title, $n, $c_id) {

        $copies = \R::dispense('copy', $n);
		$barcodes = \R::dispense('barcode', $n);
        $coll = \R::load('collection', $c_id);
        
		if (!is_array($copies)) {
			$copies = array($copies);
			$barcodes = array($barcodes);
		}

		foreach($copies as $i => $copy) {
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
		\R::store($title);
    }
    
    static function getIDs() {
        if (isset($_GET['collection'])) {
            if ($_GET['collection'] == 'all') {
                $app = \Base::instance();
                $app->reroute('/title/all');
            }
            $rows = \R::getAll('SELECT DISTINCT title_id AS id FROM copy WHERE collection_id = ?', array($_GET['collection']));
		} else {    
            $rows = \R::getAll('SELECT id FROM title ORDER BY author, title');
		}
		return array_map(function($a) { return $a['id']; }, $rows);
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
    
    
    function delete($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        
        $title = \R::load('title', $params['id']);
        if ($title) {
            $data = serialize($title->export());
            new \controllers\Log('delete', 'Deleted title "'. $title->title.'"', $data);
            \R::trashAll($title->ownCopy);
            \R::trash($title);
        }

        echo $app->get('BASE').'/title/all';
    }
    
}
