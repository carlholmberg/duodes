<?php

/**
 * Report controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
/* Model: Report
    id [auto]
    name [str]
    html [str]
    data [str] (i.e. #lname#=>user.lastname)
*/
    
namespace app;

class Report extends ViewController {
    
    function get($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Reports}';
        $this->setPage('report');
        /*
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
        }*/
    }
    
    function post($app, $params) {
       
    }
}