<?php

/**
 * Title controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Title extends \controllers\ViewController {

    function get($app, $params) {
        $id = $params['id'];
        
        switch($id) {
            case 'new':
                $this->menu = true;
                $this->footer = true;
                $title = new \models\Title();
                $title->save();
                $id = $title->data->id;
                $this->slots['id'] = $id;
                $slots = array('title' => '', 'author' => '', 'isbn' => '', 'desc' => '', 'keywords' => '', 'publisher' => '',  'date' => '', 'code' => '', 'url' => '');
                $this->addSlots($slots);
                $this->slots['pagetitle'] = 'Ny titel';
                $this->setPage('title');
                if ($this->hasLevel(4)) {
                    $this->addPiece('main', 'xeditable', 'extrahead');
                    $this->addPiece('page', 'editing', 'editing');
                }
                
                break;
            
            case 'all':
                $this->menu = true;
                $this->footer = true;
                $this->addPiece('main', 'tablesorter', 'extrahead');
                $ids = \models\Title::getIDs();
                $this->slots['pagetitle'] = '{Titles}';
                $this->slots['ids'] = count($ids);
                $this->setPage('titles');

                $header = \models\Title::getHeader($this->lvl);
                $titles = \models\Title::getTitles(0, 40);
        
                $this->buildTable($header, $titles);
                
                break;
            
            default:
                $this->menu = true;
                $this->footer = true;
                $title = new \models\Title($params['id']);
                
                if($title->exists) {
                    $copies = $title->getCopies();
                    $this->slots['id'] = $params['id'];
                    $this->addSlots($title->getData());
                    $this->slots['pagetitle'] = $this->slots['title'];
                    $this->setPage('title');
                    $this->addPiece('main', 'tablesorter', 'extrahead');
                    if ($this->hasLevel(4)) {
                        $this->addPiece('main', 'xeditable', 'extrahead');
                        $this->addPiece('page', 'editing', 'editing');
                        $this->addPiece('page', 'isbnedit', 'isbnedit');
                    }
                    if (count($copies)) {
                        $header = \models\Copy::getHeader($this->lvl);
                        if($this->hasLevel(4)) $this->buildCopyTable($header, $copies);
                        else $this->buildTable($header, $copies);
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
                $res = self::queryLibris($isbn);
                if (!$res) {
                    $res = self::queryOpenLib($isbn);
                }
                $title = new \models\Title($params['id']);
                $title->update($res);
                $title->save();
                
                echo $this->app->get('BASE').'/title/'.$params['id'];
                die();
            }
        }
        
        if (isset($params['action'])) {
            $copies = $app->get('POST.copies');
            $collection = $app->get('POST.collection');
            
            $title = new \models\Title($params['id']);
            $title->addCopies($copies, $collection);
                        
            $app->reroute('/title/'.$params['id']);
        }
        
        $field = $app->get('POST.name');
        $id = $app->get('POST.pk');
        $value = $app->get('POST.value');
        
        if ($value == '' || $value[0] == '[') {
            echo "Ogiltigt värde";
            die();
        } else {
            $title = new \models\Title($id);
            $title->update(array($field => $value));
            $title->save();
        }
    }
    
    function put() {}
    
    
    function delete($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        
        $title = new \models\Title($params['id']);
        $data = serialize($title->data->export());
        new \controllers\Log('delete', 'Deleted title "'. $title->data->title.'"', $data);

        $title->deleteCopies();
        $title->delete();

        echo $this->app->get('BASE').'/title/all';
    }
    
    static function queryLibris($isbn) {
		try {
		    $query = "http://libris.kb.se/xsearch?query=isbn:(".$isbn.")&format_level=full&format=json";
			$res = json_decode(file_get_contents($query), true);
			$res = $res['xsearch']['list'];
			if (!is_array($res)) return false;
			$result = array();
			foreach ($res as $listing) {
				$result[] = self::cleanListing($listing, $isbn);
			}
			return $result[0];
		} catch (Exception $e) {
			return false;
		}
	}
	
    
    static function queryOpenLib($isbn) {
        try {
            $query = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&jscmd=data";
			$res = json_decode(file_get_contents($query), true);
			die(var_dump($res));
			if (!is_array($res)) return false;
			$result = array();
			foreach ($res as $listing) {
				$result[] = self::cleanListing($listing, $isbn);
			}
			return $result;
		} catch (Exception $e) {
			return false;
		}
    
    }


	
	private static function cleanText($array, $text) {
		if (!isset($array[$text])) return '';
		$text = $array[$text];
		if (is_array($text)) { $text = implode("\n", $text); }
		return trim($text);
	}
	
	private static function cleanListing($listing, $isbn) {
		$new = array();
		$new['title'] = self::cleanText($listing, 'title');
		$new['author'] = self::cleanText($listing, 'creator');
		$new['isbn'] = $isbn;
		$new['date'] = self::cleanText($listing, 'date');
		$new['publisher'] = self::cleanText($listing, 'publisher');
		$new['url'] = self::cleanText($listing, 'identifier');
		$new['desc'] = self::cleanText($listing, 'description');
		$new['lang'] = self::cleanText($listing, 'lang');
		if (isset($listing['classification'])) {
		    $code = explode(' ', self::cleanText($listing['classification'], 'sab'));
			$new['code'] = $code[0];
		} else {
			$new['code'] = '';
		}
		$new['keywords'] = self::cleanText($listing, 'subject');

		return $new;
	}
    
}
