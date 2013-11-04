<?php

/**
 * Circ controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Circ extends \app\ViewController {


    function get($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->setPage('circ');
        $this->slots['pagetitle'] = '{Circ}';
        
        if ($app->get('SESSION.circ')) {
            $this->slots['active'] = $app->get('SESSION.circ');
            $app->set('SESSION.circ', false);
        } else {
            $this->slots['active'] = 'return';
        }
    }
    
    
    function borrow($user, $copy) {
        $data = array('user' => $user->email, 'copy' => $copy->id, date('Y-m-d H:i'));
        if ($copy->user) {
            new Log('borrow', $user->email.' tried to borrowed copy "'. $copy->barcode.'"', serialize($data));
            $this->app->set('SESSION.msg', 'Boken är redan utlånad');
            $this->app->set('SESSION.circ', 'borrow');
            $this->app->reroute('/circ');
        }
        if ($copy->collection) {
            if ($copy->collection->type == 'days') {
                $copy->return_date = strtotime('+ '.$copy->collection->value.' days');
            } else if ($copy->collection->type == 'ref') {
                new Log('borrow', $user->email.' tried to borrowed copy "'. $copy->barcode.' (ref)"', serialize($data));
                $this->app->set('SESSION.msg', 'Boken är får inte lånas');
                $this->app->set('SESSION.circ', 'borrow');
                $this->app->reroute('/circ');
            } else if ($copy->collection->type == 'fixed') {
                if ($this->app->get('SESSION.FixedCollDate')) {
                    $copy->return_date = $this->app->get('SESSION.FixedCollTS');
                } else {
                    $ts = unserialize($copy->collection->value);
                    $copy->return_date = $ts[0]['ts'];
                }
            }
        }
        new Log('borrow', $user->email.' borrowed copy "'. $copy->barcode.'"', serialize($data));
        
        $user->ownCopy[] = $copy;
        Title::updateBorrowed($copy->title);
        \R::store($user);     
           
        $name = $user->firstname.' '.$user->lastname;
        $this->app->set('SESSION.msg', $name.' lånade '.$copy->title->title);
        $this->app->set('SESSION.circ', 'borrow');
        $this->app->reroute('/circ');
    }
    
    function returnCopy($copy) {
        unset($copy->user);
        Title::updateBorrowed($copy->title);
        \R::store($copy);
    }
    
    function post($app, $params) {
        if ($app->get('POST.barcode')) {
            $cid = strtoupper(trim($app->get('POST.barcode')));
            $copy = \R::findOne('copy', ' barcode = ? ', array($cid));
            if ($copy) {
                if ($copy->user) {
                    $data = array('user' => $copy->user->email, 'date' => date('Y-m-d H:i'));
                    new Log('return', 'Returned copy "'. $copy->barcode.'"', serialize($data));
                    $user = $copy->user;
                    
                    $this->returnCopy($copy);
                    $name = $user->firstname.' '.$user->lastname;
                    $collection = ($copy->collection)? $copy->collection->name : '';
                    $msg = sprintf("%s återlämnade %s (%s). [%s]", $name, $copy->title->title, $copy->barcode, $collection);
                    $app->set('SESSION.msg', $msg);
                    $app->set('SESSION.circ', 'return');
                    $app->reroute('/circ');
                } else {
                    $data = array('date' => date('Y-m-d H:i'));
                    new Log('return', 'Tried to return copy "'. $copy->barcode.'"', serialize($data));
                    $app->set('SESSION.msg', 'Försökte återlämna '.$copy->title->title.' som inte är utlånad');
                    $app->set('SESSION.circ', 'return');
                    $app->reroute('/circ');
                }
            }
            
            $app->set('SESSION.msg', 'Felaktig streckkod');
            $app->set('SESSION.circ', 'return');
            $app->reroute('/circ');
        } else {
            $b1 = strtoupper(trim($app->get('POST.bc1')));
            $b2 = strtoupper(trim($app->get('POST.bc2')));
            
            // b1: copy, b2: user
            $copy = \R::findOne('copy', ' barcode = ? ', array($b1));
            if ($copy) {
                $user = \R::findOne('user', ' barcode = ? ', array($b2));
                if ($user) {    
                    $this->borrow($user, $copy);
                }
            }
            
            // b2: copy, b1: user
            $copy = \R::findOne('copy', ' barcode = ? ', array($b2));
            if ($copy) {
                $user = \R::findOne('user', ' barcode = ? ', array($b1));
                if ($user) {
                     $this->borrow($user, $copy);
                }
            }
            
            $app->set('SESSION.msg', 'Felaktiga streckkoder');
            $app->set('SESSION.circ', 'borrow');
            $app->reroute('/circ');
        }
    
    }
    
    function put() {}
}