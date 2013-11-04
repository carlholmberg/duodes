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
    
    function search($app, $params) {
        $terms = $app->get('GET.search');
        if ($this->hasLevel(3)) {
            $user = \R::findOne('user', ' barcode = ? ', array($terms));
            if ($user) {
                $app->reroute('/user/'.$user->id);
            }
        }
        
        $copy = \R::findOne('copy', ' barcode = ? ', array($terms));
        if ($copy) {
            $app->reroute('/title/'.$copy->title->id);
        }
        
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Search}';
        
        if ($this->hasLevel(3)) {
            $this->setPage('search-both');
        } else {
            $this->setPage('search');
        }
        $terms = $app->get('GET.search');
        if (substr($terms, 0, 1) == '"') $terms = array(str_replace('"', '', $terms));
        else $terms = explode(' ', $terms);

        $results = array();
        foreach ($terms as $term) {
            $titles = \R::findAll('title', ' title LIKE :term OR author LIKE :term OR keywords LIKE :term COLLATE NOCASE ', array(':term' => '%'.$term.'%'));
            $results = array_merge($results, $titles);
        }
        if (count($results) == 0) {
            $this->addPiece('page', 'noresult', 'resultTitle', array('what' => '{titles}'));
        }
        foreach ($results as $result) {
            $this->addPiece('page', 'resultTitle', 'resultTitle', array('id' => $result->id, 'title' => $result->title, 'author' => $result->author, 'date' => $result->date));
        }
        if ($this->hasLevel(3)) {
            $results = array();
            foreach ($terms as $term) {
                $term = '%'.$term.'%';
                $users = \R::findAll('user', ' firstname LIKE :term OR lastname LIKE :term COLLATE NOCASE ', array(':term' => $term));
                $results = array_merge($results, $users);
            }
            if (count($results) == 0) {
                $this->addPiece('page', 'noresult', 'resultUser', array('what' => '{users}'));
            }
            foreach ($results as $result) {
                $this->addPiece('page', 'resultUser', 'resultUser', array('id' => $result->id, 'lastname' => $result->lastname, 'firstname' => $result->firstname, 'class' => $result->class));
            }
        }
                    
        
    }
    
    function e404() {
        $this->setPage('404');
    }
    
    function label($app, $params) {
        echo 'Label-page';
    }


    function titles_ajax($app, $params) {
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        $header = \models\Title::getHeader($this->lvl);
        $titles = Title::getTitles($from, $to);
        
        $tpl = $this->buildTable($header, $titles, true);
        
        $this->tpl = false;
        echo $tpl;
    }
    
    function users_ajax($app, $params) {
        $from = (int)$params['from'];
        $to = (int)$params['to'];
        $header = \models\User::getHeader($this->lvl);
        $users = User::getUsers($from, $to);
        
        $tpl = $this->buildTable($header, $users, true);
        
        $this->tpl = false;
        echo $tpl;
    }
        
    function report($app, $params) {
        echo 'Report-page';
    }
}