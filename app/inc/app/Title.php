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

namespace app;

class Title extends \app\ViewController {

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
                if ($app->get('GET.collection')) {
                    $app->reroute('/title/c/'.$app->get('GET.collection'));
                }
                $this->menu = true;
                $this->footer = true;
                $this->addPiece('main', 'tablesorter', 'extrahead');
                $rows = \R::getCell('SELECT COUNT(*) FROM title');
                $this->slots['pagetitle'] = '{Titles}';
                $this->slots['ids'] = $rows;
                $this->setPage('titles');

                $header = self::getHeader($this->lvl);
                $titles = Title::getTitles(0, 40);
        
                $this->buildTable($header, $titles);
                
                break;
            
            default:
                $this->menu = true;
                $this->footer = true;
                
                $title = \R::load('title', $id);
                if ($title) {
                    $slots = array('id' => $id, 'title' => '', 'author' => '', 'isbn' => '', 'desc' => '', 'keywords' => '', 'publisher' => '',  'date' => '', 'code' => '', 'url' => '', 'registered' => date('Y-m'), 'total' => '0', 'borrowed' => '0', 'lang' => '', 'image' => '');
                    
                    if ($this->hasLevel(4)) {
                        $this->setPage('title-edit');
                        $this->addPiece('main', 'xeditable', 'extrahead');
                    } else {
                        $this->setPage('title');
                    }
                    
                    $this->addSlots($slots);
                    
                    if ($app->get('SESSION.newtitle')) {
                        $app->set('SESSION.newtitle', false);
                    
                        $this->slots['pagetitle'] = 'Ny titel';

                    } else {
                        $copies = Copy::getCopies($title, $this->lvl);
                        $data = $title->export(false, false, true);
                        $data['image'] = $data['isbn'];
                        if (!is_numeric($data['isbn'])) $data['image'] = 'missing';
                        $this->addSlots($data);
                        if (isset($this->slots['title'])) {
                            $this->slots['pagetitle'] = $this->slots['title'];
                        } else {
                            $this->slots['pagetitle'] = 'Namnlös titel';
                        }     
                        $this->addPiece('main', 'tablesorter', 'extrahead');
                        if (count($copies)) {
                            $header = Copy::getHeader($this->lvl);
                            if($this->hasLevel(4)) $this->buildCopyTable($header, $copies);
                            else $this->buildTable($header, $copies);
                        }
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
			$copy->barcode = strtoupper(base_convert($title->id, 10, 36).'.'.base_convert($c_id, 10, 36)); 
		
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
    
    static function getIDs($coll=false) {
        if ($coll) {
            $rows = \R::getCol('SELECT DISTINCT title_id FROM copy WHERE collection_id = ?', array($coll));
		} else {    
            $rows = \R::getCol('SELECT id FROM title ORDER BY author, title');
		}
		return $rows;
    }
    
    static function updateBorrowed($title) {
	    $borrowed = 0;
	    foreach($title->ownCopy as $copy) {
	        if ($copy->user) {
	            $borrowed += 1;
	        }
	    }
	    $title->borrowed = $borrowed;
	    $title->total = count($title->ownCopy);
	    \R::store($title);
	}
    
    static function getTitles($from=0, $to=false, $coll=false) {
        $ids = self::getIDs($coll);
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
            $this->addMessage('titel_del', array('title'=>$title->title, 'author'=>$title->author));
            new Log('delete', 'Deleted title "'. $title->title.'"', $data);
            \R::trashAll($title->ownCopy);
            \R::trash($title);
            
        }

        echo $app->get('BASE').'/title/all';
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
        return $header;
    }
    
    function collection($app, $params) {
        $id = $params['id'];
        if ($id == 'all') $app->reroute('/title/all');
        $this->menu = true;
        $this->footer = true;
        $this->addPiece('main', 'tablesorter', 'extrahead');
        $rows = \R::getCell('SELECT COUNT(DISTINCT title_id) FROM copy WHERE collection_id = ?', array($id));
        $collection = \R::load('collection', $id);
        $this->slots['pagetitle'] = '{Titles} {in} "'.$collection->name.'"';
        $this->slots['ids'] = $rows;
        $this->setPage('titles');

        $header = self::getHeader($this->lvl);
        $titles = Title::getTitles(0, 40, $id);

        $this->buildTable($header, $titles);
    }        
    
    function titles_ajax($app, $params) {
        $id = $params['id'];
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        
        $header = Title::getHeader($this->lvl);
        $titles = Title::getTitles($from, $to, $id);
        
        $tpl = $this->buildTable($header, $titles, true);
        
        $this->tpl = false;
        echo $tpl;
    }
    
}
