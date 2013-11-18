<?php

/**
 * Barcode controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
/* Model: Barcode
    id [auto]
    barcode [str]
    text [str]
    type [str] (user/copy)
    b_id [str] (user/title)
*/
    
namespace app;

class Barcode extends ViewController {
    
    function get($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Barcodes}';
        $this->setPage('barcode');
        
        $row = $this->loadTpl('barcode-row');
        
        $users = \R::findAll('barcode', ' type = "user" ');
        $copies = \R::findAll('barcode', ' type = "copy" ');
        
        $c_count = array();
        
        foreach ($copies as $b) {
            $c_count[$b->b_id] = isset($c_count[$b->b_id])? $c_count[$b->b_id]+1 : 1;
        }
        $u_count = count($users);
        if ($u_count) {
            $this->addPiece('page', 'row', 'row', array('type'=>'user', 'title'=>'{Users}', 'n'=>$u_count, 'id'=>'user'));
        }
        foreach($c_count as $id=>$n) {
            $title = \R::load('title', $id);
            $this->addPiece('page', 'row', 'row', array('type'=>'book', 'title'=>$title->title, 'n'=>$n, 'id'=>$id));
        }
    }
    
    function post($app, $params) {
       
    }
   
    function put() {}
    
    function delete($app, $params) {
        $id = $params['id'];
        if ($id == 'user') {
            $barcodes = \R::findAll('barcode', ' type = "user" ');
            $data = array();
            foreach($barcodes as $bc) {
                $data[] = $bc->b_id;
            }
            new Log('barcode', 'Cleared barcodes for users', serialize($data));
            \R::trashAll($barcodes);
            $app->reroute('/barcode');
        } else if ($id == 'all') {
            $barcodes = \R::findAll('barcode');
            $data = array();
            foreach($barcodes as $bc) {
                $data[] = array('type'=> $bc->type, 'b_id' => $bc->b_id);
            }
            new Log('barcode', 'Cleared all barcodes', serialize($data));
            \R::trashAll($barcodes);
            $app->reroute('/barcode');
        }
        $barcodes = \R::findAll('barcode', ' type = "copy" AND b_id = ?', array($id));
        $title = \R::load('title', $id);
        new Log('barcode', 'Cleared barcodes for title '.$title->title, $id);
        \R::trashAll($barcodes);
        $app->reroute('/barcode');
    }
}