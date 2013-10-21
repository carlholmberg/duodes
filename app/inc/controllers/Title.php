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
        switch ($params['id']) {
            case 'new':
                echo 'New title';
                break;
            default:
                $this->menu = true;
                $this->footer = true;
                $title = new \models\Title($params['id']);
                $copies = $title->getCopies();
                $this->slots['pagetitle'] = '{Title}';
                $this->slots['id'] = $params['id'];
                $this->slots['title'] = $title->data->title;
                $this->setPage('title');
                $this->addPiece('main', 'tablesorter', 'extrahead');
                $this->addPiece('main', 'xeditable', 'extrahead');
                break;
        }
    }
    function post($app, $params) {}
    function put() {}
    function delete() {}
    
    function buildTable($copies) {
    
    }
}
