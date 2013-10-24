<?php

/**
 * Main controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Main extends \controllers\ViewController {
    
    function minify($app, $params) {
        $app->set('UI', $params['type'] . '/');
        echo \Web::instance()->minify($_GET['files']);
    }

    function index($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Home}';
        $this->setPage('index');
    }
    
    function login($app, $params) {
        $this->slots['pagetitle'] = '{Login}';
        $this->setPage('login');
    }
    
    function noaccess($app, $params) {
        $this->slots['pagetitle'] = '{No access}';
        $this->setPage('noaccess');
    }
    
    function e404() {
        $this->setPage('404');
    }
    
    function label($app, $params) {
        echo 'Label-page';
    }
    
    function copy($app, $params) {
        echo 'Copy-page';
    }

    function titles($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->addPiece('main', 'tablesorter', 'extrahead');
        $ids = \models\Title::getIDs();
        $this->slots['pagetitle'] = '{Titles}';
        $this->setPage('titles');
        
        $header = array('title' => array('class' => '', 'placeholder' => '', 'name' => '{Title}'), 'author' => array('class' => '', 'placeholder' => '', 'name' => '{Author}'), 'date' => array('class' => '', 'placeholder' => '', 'name' => '{Year}'), 'total' => array('class' => '', 'placeholder' => '', 'name' => '{Total}'), 'borrowed' => array('class' => '', 'placeholder' => '', 'name' => '{Borrowed}'));
        foreach ($header as $key=>$val) {
            $this->addPiece('page', 'hcell', 'hcell', $val);
            $this->addPiece('page', 'fcell', 'fcell', $val);
        }
        $titles = \models\Title::getTitles(0, 40);
        $tpl = '
	    <!-- cut:row -->
        <tr>
            
	        <!-- cut:acell -->
	        <td><a href="title/#id#" title="#cell#">#cell#</a></td>
	        <!-- /cut:acell -->
	        
	        <!-- cut:cell -->
	        <td>#cell#</td>
	        <!-- /cut:cell -->
	        <!-- paste:cell -->
        </tr>
	    <!-- /cut:row -->
	    <!-- paste:row -->
	    ';
        $tpl = new \StampTE($tpl);
        $id = 0;
        foreach ($titles as $title) {
            $row = $tpl->get('row');
            $id = $title['id'];
            foreach(array_keys($header) as $cell) {
                if ($cell == 'title') {
                    if ($title[$cell] == '') $title[$cell] = '[{No title}]';

                    $row->glue('cell', $row->get('acell')->injectAll(array('id'=>$id, 'cell'=>$title[$cell])));
                } else
                    $row->glue('cell', $row->get('cell')->inject('cell', $title[$cell]));
            }
            $tpl->glue('row', $row);
        }
        $this->addPiece('page', $tpl, 'tbody');
    }
    
    function titles_ajax($app, $params) {
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        $header = array('title', 'author', 'date', 'total', 'borrowed');
        $titles = \models\Title::getTitles($from, $to);
        $tpl = '
	    <!-- cut:row -->
        <tr>
            
	        <!-- cut:acell -->
	        <td><a href="title/#id#" title="#cell#">#cell#</a></td>
	        <!-- /cut:acell -->
	        
	        <!-- cut:cell -->
	        <td>#cell#</td>
	        <!-- /cut:cell -->
	        <!-- paste:cell -->
        </tr>
	    <!-- /cut:row -->
	    <!-- paste:row -->
	    ';
        $tpl = new \StampTE($tpl);
        foreach ($titles as $title) {
            $row = $tpl->get('row');
            $id = $title['id'];
            foreach($header as $cell) {
                if ($cell == 'title') {
                    if ($title[$cell] == '') $title[$cell] = '[{No title}]';
                    $row->glue('cell', $row->get('acell')->injectAll(array('id'=>$id, 'cell'=>$title[$cell])));
                }
                else
                    $row->glue('cell', $row->get('cell')->inject('cell', $title[$cell]));
            }
            $tpl->glue('row', $row);
        }
        $this->tpl = false;
        echo $tpl;
    }
        
    
    function user($app, $params) {
        echo 'User-page';
    }
    
    function report($app, $params) {
        echo 'Report-page';
    }
}