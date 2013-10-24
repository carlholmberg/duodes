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
        if ($id == 'new') {
            echo 'New title';
        } else {
            $this->menu = true;
            $this->footer = true;
            $title = new \models\Title($params['id']);
            if($title) {
                $copies = $title->getCopies();
                $this->slots['pagetitle'] = '{Title}';
                $this->slots['id'] = $params['id'];
                $this->slots = array_merge($this->slots, $title->getData());
                $this->setPage('title');
                $this->addPiece('main', 'tablesorter', 'extrahead');
                if ($this->hasLevel(3)) {
                    $this->addPiece('main', 'xeditable', 'extrahead');
                    $this->addPiece('page', 'editing', 'editing');
                }
                if (count($copies)) {
                    $this->addPiece('page', 'copies', 'copies');
                }
                
                $table = $this->loadTpl('tablesorter');
                
                
            } else {
                   
            }
        }
    }
    function post($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        $field = $app->get('POST.name');
        $id = $app->get('POST.pk');
        $value = $app->get('POST.value');
        if ($value == '' || $value[0] == '[') {
            echo "Ogiltigt värde";
            header('Content-type: application/json');
        } else {
            $title = new \models\Title($id);
            $title->update(array($field => $value));
            $title->save();
        }
    }
    function put() {}
    function delete() {}
    
    function buildTable($copies) {
    
    }
}
