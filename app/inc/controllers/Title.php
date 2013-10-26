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
            if($title->exists) {
                $copies = $title->getCopies();
                $this->slots['id'] = $params['id'];
                $this->addSlots($title->getData());
                $this->slots['pagetitle'] = $this->slots['title'];
                $this->setPage('title');
                $this->addPiece('main', 'tablesorter', 'extrahead');
                if ($this->hasLevel(3)) {
                    $this->addPiece('main', 'xeditable', 'extrahead');
                    $this->addPiece('page', 'editing', 'editing');
                }
                if (count($copies)) {
                    $header = \models\Copy::getHeader($this->lvl);
                    
                    $this->buildTable($header, $copies);
                }
                
                
                
            } else {
                $app->reroute('/titles');
            }
        }
    }
    
    
    function post($app, $params) {
        $this->reqLevel(3);
        $this->tpl = false;
        if (isset($params['action'])) {
            $copies = $app->get('POST.copies');
            $collection = $app->get('POST.collection');
            
            // Lägg till kopior till titel
            
            $app->reroute('/title/'.$params['id']);
        }
        $field = $app->get('POST.name');
        $id = $app->get('POST.pk');
        $value = $app->get('POST.value');
        if ($value == '' || $value[0] == '[') {
            echo "Ogiltigt värde";
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

        $title->delete();

        echo $this->app->get('BASE').'/titles';
    }
    
}
